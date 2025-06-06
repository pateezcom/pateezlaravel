'use strict';

/**
 * Language Settings Form Validation
 * Dil Ayarları Form Doğrulama
 */

// Global değişkenler
let fvAddLanguage, fvEditLanguage;

document.addEventListener('DOMContentLoaded', function () {
  // Sayfa yenileme fonksiyonu
  function pencereYenile() {
    setTimeout(function () {
      window.location.reload();
    }, 1500);
  }

  // Modal olayları için basit temizleme işlemleri
  $('#importLanguageModal').on('show.bs.modal', function () {
    const importLanguageForm = document.getElementById('importLanguageForm');
    if (importLanguageForm) {
      importLanguageForm.reset();
      $(importLanguageForm).find('.invalid-feedback').text('');
      $(importLanguageForm).find('.is-invalid').removeClass('is-invalid');
    }
  });

  $('#importLanguageModal').on('hidden.bs.modal', function () {
    const importLanguageForm = document.getElementById('importLanguageForm');
    if (importLanguageForm) {
      importLanguageForm.reset();
      $(importLanguageForm).find('.invalid-feedback').text('');
      $(importLanguageForm).find('.is-invalid').removeClass('is-invalid');
    }
  });

  // Debounce fonksiyonu
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Tablo yenileme için debounced fonksiyon
  const debouncedRefreshTable = debounce(() => {
    if (typeof window.refreshLanguageTable === 'function') {
      window.refreshLanguageTable();
    }
  }, 300);

  // Benzersizlik kontrolü için AJAX fonksiyonu
  function checkFieldUnique(field, value, excludeId = null) {
    return new Promise(resolve => {
      $.ajax({
        url: '/admin/settings/languages/check-unique',
        method: 'POST',
        data: { field, value, exclude_id: excludeId },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          resolve(response.unique === true);
        },
        error: function () {
          resolve(true); // Hata durumunda devam et
        }
      });
    });
  }

  // Hata mesajlarını temizleme fonksiyonu - açıklama metinlerini korur
  function clearFieldErrors(form) {
    if (!form) return;

    const invalidFields = form.querySelectorAll('.is-invalid');
    const feedbackElements = form.querySelectorAll('.invalid-feedback');

    invalidFields.forEach(field => {
      field.classList.remove('is-invalid');
    });

    feedbackElements.forEach(element => {
      element.textContent = '';
    });
  }

  // Formları sıfırlama ve temizleme fonksiyonu
  function resetForm(form, formValidation) {
    if (!form) return;

    // 1. Form elementini sıfırla
    form.reset();

    // 2. Hata mesajlarını temizle
    clearFieldErrors(form);

    // 3. FormValidation instance'ı sıfırla
    if (formValidation) {
      try {
        formValidation.resetForm(true);
      } catch (error) {
        // Form validation reset hatası
      }
    }

    // 4. Select2 sıfırlama
    if ($.fn.select2) {
      try {
        $(form).find('select').val('').trigger('change.select2');
      } catch (error) {
        // Select2 reset hatası
      }
    }

    // 5. Submit butonunu sıfırla
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.disabled = false;
      if (form.id === 'addLanguageForm') {
        submitButton.innerHTML = 'Kaydet';
      } else if (form.id === 'editLanguageForm') {
        submitButton.innerHTML = 'Güncelle';
      }
    }

    // 6. Gizli ID alanını sıfırla (düzenleme modalı için)
    if (form.id === 'editLanguageForm') {
      const idField = form.querySelector('[name="editLanguageId"]');
      if (idField) idField.value = '';
    }
  }

  // EKLEME FORMU DOĞRULAMASI
  const addLanguageForm = document.getElementById('addLanguageForm');
  window.addEventListener('translationsLoaded', function () {
    if (addLanguageForm) {
      try {
        fvAddLanguage = FormValidation.formValidation(addLanguageForm, {
          fields: {
            languageName: {
              validators: {
                notEmpty: {
                  message: __('msg_language_name_required')
                },
                stringLength: {
                  min: 2,
                  max: 50,
                  message: __('msg_language_name_length')
                }
              }
            },
            shortForm: {
              validators: {
                notEmpty: {
                  message: __('msg_short_form_required')
                },
                stringLength: {
                  min: 2,
                  max: 5,
                  message: __('msg_short_form_length')
                },
                regexp: {
                  regexp: /^[a-zA-Z]+$/,
                  message: __('msg_short_form_invalid')
                }
              }
            },
            languageCode: {
              validators: {
                notEmpty: {
                  message: __('msg_language_code_required')
                },
                stringLength: {
                  min: 2,
                  max: 10,
                  message: __('msg_language_code_length')
                },
                regexp: {
                  regexp: /^[a-zA-Z_]+$/,
                  message: __('msg_language_code_invalid')
                }
              }
            },
            orderInput: {
              validators: {
                notEmpty: {
                  message: __('msg_order_required')
                },
                numeric: {
                  message: __('msg_order_invalid')
                }
              }
            },
            textEditorLanguage: {
              validators: {
                notEmpty: {
                  message: __('msg_text_editor_language_required')
                }
              }
            }
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
              eleValidClass: '',
              rowSelector: function (field, ele) {
                return '.col-md-12, .col-md-6'; // Bootstrap grid sınıflarına göre
              }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
          }
        });

        fvAddLanguage.on('core.form.valid', async function () {
          // Form doğrulama başarılı, şimdi benzersizlik kontrolü yapalım
          const languageName = addLanguageForm.querySelector('[name="languageName"]').value;
          const shortForm = addLanguageForm.querySelector('[name="shortForm"]').value;
          const languageCode = addLanguageForm.querySelector('[name="languageCode"]').value;

          // Önce tüm hata mesajlarını temizleyelim
          clearFieldErrors(addLanguageForm);

          // Benzersizlik kontrollerini yapalım
          const uniqueChecks = [
            {
              field: 'languageName',
              value: languageName
            },
            {
              field: 'shortForm',
              value: shortForm
            },
            {
              field: 'languageCode',
              value: languageCode
            }
          ];

          let isValid = true;

          for (const check of uniqueChecks) {
            const isUnique = await checkFieldUnique(check.field, check.value);
            if (!isUnique) {
              isValid = false;
              // Form geçerli değil, hata mesajı göster
              const element = addLanguageForm.querySelector(`[name="${check.field}"]`);
              if (element) {
                element.classList.add('is-invalid');

                // Hata mesajı ekle
                const feedbackElement = element.nextElementSibling;
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                  const fieldLabels = {
                    languageName: __('language_name'),
                    shortForm: __('short_form'),
                    languageCode: __('language_code')
                  };
                  const label = fieldLabels[check.field] || __('field');
                  feedbackElement.textContent = `${label} ${__('msg_already_exists')}`;
                }

                if (fvAddLanguage) {
                  // Hata mesajını doğrudan FormValidation ile güncelle
                  fvAddLanguage.revalidateField(check.field);
                }
              }
            }
          }

          if (isValid) {
            // Tüm kontroller başarılı, formu gönder
            submitAddLanguageForm();
          }
        });

        // Input değiştiğinde hata durumunu sıfırla
        addLanguageForm.querySelectorAll('input, select').forEach(input => {
          input.addEventListener('input', function () {
            const field = this.name;
            if (field) {
              const element = this;
              const feedbackElement = element.nextElementSibling;

              if (element.classList.contains('is-invalid')) {
                element.classList.remove('is-invalid');
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                  feedbackElement.textContent = '';
                }
              }
            }
          });
        });

        // Form submit olayı
        addLanguageForm.addEventListener('submit', function (e) {
          e.preventDefault();

          // Önce hata mesajlarını temizle
          clearFieldErrors(this);

          // Formu doğrula
          if (fvAddLanguage) {
            fvAddLanguage.validate();
          }
        });
      } catch (error) {
        // Form validation yükleme hatası
      }

      // Form gönderme işlemi
      function submitAddLanguageForm() {
        const submitButton = addLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
            __('txt_processing');
        }

        const formData = new FormData(addLanguageForm);

        $.ajax({
          url: '/admin/settings/languages',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.success) {
              // Form başarıyla gönderildi
              $('#addLanguageModal').modal('hide');

              // Başarı mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showSuccess(__('msg_language_added'), __('success'));
                pencereYenile();
              } else {
                Swal.fire({
                  icon: 'success',
                  title: __('success'),
                  text: __('msg_language_added'),
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                }).then(function () {
                  // Sayfa yenilemesi yap
                  window.location.reload();
                });
              }
            } else if (response.errors) {
              // Sunucudan gelen doğrulama hataları
              for (const [field, messages] of Object.entries(response.errors)) {
                const element = addLanguageForm.querySelector(`[name="${field}"]`);
                if (element) {
                  element.classList.add('is-invalid');

                  const feedbackElement = element.nextElementSibling;
                  if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = messages[0];
                  }
                }
              }
              // Submit butonunu sıfırla
              const submitButton = addLanguageForm.querySelector('button[type="submit"]');
              if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = __('save');
              }
            }
          },
          error: function (xhr) {
            // Submit butonunu sıfırla
            const submitButton = addLanguageForm.querySelector('button[type="submit"]');
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = __('save');
            }

            let errorMessage = __('msg_error');

            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              // Doğrulama hataları
              const errors = xhr.responseJSON.errors;
              for (const [field, messages] of Object.entries(errors)) {
                const element = addLanguageForm.querySelector(`[name="${field}"]`);
                if (element) {
                  element.classList.add('is-invalid');

                  const feedbackElement = element.nextElementSibling;
                  if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = messages[0];
                  }
                }
              }
            } else {
              // Genel hata
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(errorMessage, __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: errorMessage,
                  customClass: {
                    confirmButton: 'btn btn-danger'
                  }
                });
              }
            }
          }
        });
      }
    }

    // DÜZENLEME FORMU DOĞRULAMASI
    const editLanguageForm = document.getElementById('editLanguageForm');

    if (editLanguageForm) {
      try {
        fvEditLanguage = FormValidation.formValidation(editLanguageForm, {
          fields: {
            editLanguageName: {
              validators: {
                notEmpty: {
                  message: __('msg_language_name_required')
                },
                stringLength: {
                  min: 2,
                  max: 50,
                  message: __('msg_language_name_length')
                }
              }
            },
            editShortForm: {
              validators: {
                notEmpty: {
                  message: __('msg_short_form_required')
                },
                stringLength: {
                  min: 2,
                  max: 5,
                  message: __('msg_short_form_length')
                },
                regexp: {
                  regexp: /^[a-zA-Z]+$/,
                  message: __('msg_short_form_invalid')
                }
              }
            },
            editLanguageCode: {
              validators: {
                notEmpty: {
                  message: __('msg_language_code_required')
                },
                stringLength: {
                  min: 2,
                  max: 10,
                  message: __('msg_language_code_length')
                },
                regexp: {
                  regexp: /^[a-zA-Z_]+$/,
                  message: __('msg_language_code_invalid')
                }
              }
            },
            editOrderInput: {
              validators: {
                notEmpty: {
                  message: __('msg_order_required')
                },
                numeric: {
                  message: __('msg_order_invalid')
                }
              }
            },
            editTextEditorLanguage: {
              validators: {
                notEmpty: {
                  message: __('msg_text_editor_language_required')
                }
              }
            }
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
              eleValidClass: '',
              rowSelector: function (field, ele) {
                return '.col-md-12, .col-md-6'; // Bootstrap grid sınıflarına göre
              }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
          }
        });

        fvEditLanguage.on('core.form.valid', async function () {
          // Form doğrulama başarılı, şimdi benzersizlik kontrolü yapalım
          const languageName = editLanguageForm.querySelector('[name="editLanguageName"]').value;
          const shortForm = editLanguageForm.querySelector('[name="editShortForm"]').value;
          const languageCode = editLanguageForm.querySelector('[name="editLanguageCode"]').value;
          const languageId = editLanguageForm.querySelector('[name="editLanguageId"]').value;

          // Önce tüm hata mesajlarını temizleyelim
          clearFieldErrors(editLanguageForm);

          // Benzersizlik kontrollerini yapalım
          const uniqueChecks = [
            {
              field: 'editLanguageName',
              value: languageName
            },
            {
              field: 'editShortForm',
              value: shortForm
            },
            {
              field: 'editLanguageCode',
              value: languageCode
            }
          ];

          let isValid = true;

          for (const check of uniqueChecks) {
            const isUnique = await checkFieldUnique(check.field, check.value, languageId);
            if (!isUnique) {
              isValid = false;
              // Form geçerli değil, hata mesajı göster
              const element = editLanguageForm.querySelector(`[name="${check.field}"]`);
              if (element) {
                element.classList.add('is-invalid');

                // Hata mesajı ekle
                const feedbackElement = element.nextElementSibling;
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                  const fieldLabels = {
                    editLanguageName: __('language_name'),
                    editShortForm: __('short_form'),
                    editLanguageCode: __('language_code')
                  };
                  const label = fieldLabels[check.field] || __('field');
                  feedbackElement.textContent = `${label} ${__('msg_already_exists')}`;
                }

                if (fvEditLanguage) {
                  // Hata mesajını doğrudan FormValidation ile güncelle
                  fvEditLanguage.revalidateField(check.field);
                }
              }
            }
          }

          if (isValid) {
            // Tüm kontroller başarılı, formu gönder
            submitEditLanguageForm();
          }
        });

        // Input değiştiğinde hata durumunu sıfırla
        editLanguageForm.querySelectorAll('input, select').forEach(input => {
          input.addEventListener('input', function () {
            const field = this.name;
            if (field) {
              const element = this;
              const feedbackElement = element.nextElementSibling;

              if (element.classList.contains('is-invalid')) {
                element.classList.remove('is-invalid');
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                  feedbackElement.textContent = '';
                }
              }
            }
          });
        });

        // Form submit olayı
        editLanguageForm.addEventListener('submit', function (e) {
          e.preventDefault();

          // Önce hata mesajlarını temizle
          clearFieldErrors(this);

          // Formu doğrula
          if (fvEditLanguage) {
            fvEditLanguage.validate();
          }
        });
      } catch (error) {
        // Edit form validation yükleme hatası
      }

      // Form gönderme işlemi
      function submitEditLanguageForm() {
        const submitButton = editLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
            __('txt_processing');
        }

        const id = editLanguageForm.querySelector('[name="editLanguageId"]').value;
        const formData = new FormData(editLanguageForm);
        formData.append('_method', 'PUT');

        $.ajax({
          url: `/admin/settings/languages/${id}`,
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.success) {
              // Form başarıyla gönderildi
              $('#editLanguageModal').modal('hide');

              // Başarı mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showSuccess(__('msg_language_updated'), __('success'));
                pencereYenile();
              } else {
                Swal.fire({
                  icon: 'success',
                  title: __('success'),
                  text: __('msg_language_updated'),
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                }).then(function () {
                  // Sayfa yenilemesi yap
                  window.location.reload();
                });
              }
            } else if (response.errors) {
              // Sunucudan gelen doğrulama hataları
              for (const [field, messages] of Object.entries(response.errors)) {
                const element = editLanguageForm.querySelector(`[name="${field}"]`);
                if (element) {
                  element.classList.add('is-invalid');

                  const feedbackElement = element.nextElementSibling;
                  if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = messages[0];
                  }
                }
              }
              // Submit butonunu sıfırla
              const submitButton = editLanguageForm.querySelector('button[type="submit"]');
              if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = __('update');
              }
            }
          },
          error: function (xhr) {
            // Submit butonunu sıfırla
            const submitButton = editLanguageForm.querySelector('button[type="submit"]');
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = __('update');
            }

            let errorMessage = __('msg_error');

            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              // Doğrulama hataları
              const errors = xhr.responseJSON.errors;
              for (const [field, messages] of Object.entries(errors)) {
                const element = editLanguageForm.querySelector(`[name="${field}"]`);
                if (element) {
                  element.classList.add('is-invalid');

                  const feedbackElement = element.nextElementSibling;
                  if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = messages[0];
                  }
                }
              }
            } else {
              // Genel hata
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(errorMessage, __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: errorMessage,
                  customClass: {
                    confirmButton: 'btn btn-danger'
                  }
                });
              }
            }
          }
        });
      }
    }
  });
  // Modal olayları
  $(document).ready(function () {
    // Modal olaylarını kontrol et

    // Yeni Dil Ekle modalı
    $('#addLanguageModal').on('show.bs.modal', function () {
      // Ekleme modalı açılıyor
      if (addLanguageForm) {
        addLanguageForm.reset();
        clearFieldErrors(addLanguageForm);

        // Submit butonunu sıfırla
        const submitButton = addLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = __('save');
        }
      }
    });

    $('#addLanguageModal').on('hidden.bs.modal', function () {
      // Ekleme modalı kapandı
      if (addLanguageForm) {
        addLanguageForm.reset();
        clearFieldErrors(addLanguageForm);

        // Submit butonunu sıfırla
        const submitButton = addLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = 'Kaydet';
        }
      }
    });

    // Düzenleme modalı
    $('#editLanguageModal').on('show.bs.modal', function () {
      // Düzenleme modalı açılıyor
      if (editLanguageForm) {
        clearFieldErrors(editLanguageForm);

        // Submit butonunu sıfırla
        const submitButton = editLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = __('update');
        }
      }
    });

    $('#editLanguageModal').on('hidden.bs.modal', function () {
      // Düzenleme modalı kapandı
      if (editLanguageForm) {
        editLanguageForm.reset();
        clearFieldErrors(editLanguageForm);

        // Submit butonunu sıfırla
        const submitButton = editLanguageForm.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = 'Güncelle';
        }

        // ID alanını temizle
        const idField = editLanguageForm.querySelector('[name="editLanguageId"]');
        if (idField) {
          idField.value = '';
        }
      }
    });
  });
});
