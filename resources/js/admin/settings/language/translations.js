/**
 * Translations Settings Javascript
 * Çeviri Ayarları Javascript Dosyası
 */

'use strict';

$(function () {
  // Form değişikliklerini takip etmek için değişken
  let formChanged = false;

  // CSRF token'ı ayarla
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Sayfa yenileme fonksiyonu
  function pencereYenile() {
    setTimeout(function () {
      window.location.reload();
    }, 1500);
  }

  // Değişiklikleri takip et
  $('.translation-input').on('input', function () {
    const originalValue = $(this).data('original-value');
    const currentValue = $(this).val();

    // Değer değiştiyse
    if (originalValue !== currentValue) {
      formChanged = true;
      $(this).addClass('border-primary');
    } else {
      $(this).removeClass('border-primary');

      // Tüm inputları kontrol et, değişiklik var mı?
      formChanged = $('.translation-input.border-primary').length > 0;
    }

    // Kaydet butonunu güncelle
    updateSaveButton();
  });

  // Kaydet butonunu güncelleme fonksiyonu
  function updateSaveButton() {
    const saveBtn = $('#saveChangesBtn');

    if (formChanged) {
      saveBtn.removeClass('btn-secondary').addClass('btn-primary');
      saveBtn.prop('disabled', false);
    } else {
      saveBtn.removeClass('btn-primary').addClass('btn-secondary');
      saveBtn.prop('disabled', true);
    }
  }

  // Sayfa yüklendiğinde kaydet butonunu güncelle
  updateSaveButton();

  // Form gönderimini yönet
  $('#translationForm').on('submit', function (e) {
    e.preventDefault();

    // Değişiklik yoksa işlemi durdur
    if (!formChanged) {
      return false;
    }

    // Sadece değişen alanları topla
    const changedInputs = $('.translation-input.border-primary');
    const formData = {};

    changedInputs.each(function () {
      const key = $(this)
        .attr('name')
        .match(/\[(.*?)\]/)[1];
      formData[key] = $(this).val();
    });

    // Kaydet butonunu devre dışı bırak
    const saveBtn = $('#saveChangesBtn');
    const originalText = saveBtn.html();
    saveBtn.html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Kaydediliyor...'
    );
    saveBtn.prop('disabled', true);

    // AJAX ile gönder
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'PUT',
        translations: formData
      },
      success: function (response) {
        // Başarılı mesajı göster
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showSuccess(response.message || 'Çeviriler başarıyla güncellendi.', 'Başarılı!');
        } else {
          Swal.fire({
            title: 'Başarılı!',
            text: response.message || 'Çeviriler başarıyla güncellendi.',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-success waves-effect waves-light'
            },
            buttonsStyling: false
          });
        }

        // Formu sıfırla
        formChanged = false;
        $('.translation-input').removeClass('border-primary');

        // Değişen değerleri yeni "orijinal" değer olarak kaydet
        changedInputs.each(function () {
          $(this).data('original-value', $(this).val());
        });

        // Kaydet butonunu güncelle
        updateSaveButton();
      },
      error: function (xhr) {
        // Hata mesajı göster
        let errorMessage = 'Çeviriler güncellenirken bir hata oluştu.';

        if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
        }

        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(errorMessage, 'Hata!');
        } else {
          Swal.fire({
            title: 'Hata!',
            text: errorMessage,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-danger waves-effect waves-light'
            },
            buttonsStyling: false
          });
        }
      },
      complete: function () {
        // Kaydet butonunu eski haline getir
        saveBtn.html(originalText);
        saveBtn.prop('disabled', false);
      }
    });

    return false;
  });

  // Yeni çeviri ekleme
  $('#btnSaveNewTranslation').on('click', function () {
    const key = $('#translationKey').val();
    const value = $('#translationValue').val();
    const group = $('#translationGroup').val() || 'default';
    const languageId = $('input[name="language_id"]').val();

    // Alanları doğrula
    if (!key) {
      $('#translationKey').addClass('is-invalid');
      $('#translationKey').next('.invalid-feedback').text('Çeviri anahtarı boş olamaz');
      return;
    } else {
      $('#translationKey').removeClass('is-invalid');
    }

    if (!value) {
      $('#translationValue').addClass('is-invalid');
      $('#translationValue').next('.invalid-feedback').text('Çeviri değeri boş olamaz');
      return;
    } else {
      $('#translationValue').removeClass('is-invalid');
    }

    // Butonu devre dışı bırak
    const btn = $(this);
    const originalText = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Kaydediliyor...');
    btn.prop('disabled', true);

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/translations/' + languageId + '/add',
      type: 'POST',
      data: {
        key: key,
        value: value,
        group: group
      },
      success: function (response) {
        if (response.success) {
          // Başarı mesajı
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showSuccess('Çeviri başarıyla eklendi.', 'Başarılı!');
            // Modalı kapat
            $('#addTranslationModal').modal('hide');
            pencereYenile();
          } else {
            Swal.fire({
              title: 'Başarılı!',
              text: 'Çeviri başarıyla eklendi.',
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              // Sayfayı yenile
              window.location.reload();
            });
          }
        } else {
          // Hata mesajı
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(response.error || 'Çeviri eklenirken bir hata oluştu.', 'Hata!');
          } else {
            Swal.fire({
              title: 'Hata!',
              text: response.error || 'Çeviri eklenirken bir hata oluştu.',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false
            });
          }
        }
      },
      error: function (xhr) {
        let errorMessage = 'İşlem sırasında bir hata oluştu.';

        if (xhr.responseJSON && xhr.responseJSON.errors) {
          const errors = xhr.responseJSON.errors;
          if (errors.key) {
            $('#translationKey').addClass('is-invalid');
            $('#translationKey').next('.invalid-feedback').text(errors.key[0]);
          }
          if (errors.value) {
            $('#translationValue').addClass('is-invalid');
            $('#translationValue').next('.invalid-feedback').text(errors.value[0]);
          }
        } else if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;

          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(errorMessage, 'Hata!');
          } else {
            Swal.fire({
              title: 'Hata!',
              text: errorMessage,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false
            });
          }
        }
      },
      complete: function () {
        // Butonu eski haline getir
        btn.html(originalText);
        btn.prop('disabled', false);
      }
    });
  });

  // Çeviri silme
  $('.btn-delete-translation').on('click', function () {
    const id = $(this).data('id');
    const key = $(this).data('key');

    // Silme onay modalnı aç
    $('#deleteTranslationId').val(id);
    $('#deleteTranslationKey').text(key);
    $('#deleteConfirmModal').modal('show');
  });

  // Çeviri silme onayı
  $('#btnConfirmDelete').on('click', function () {
    const id = $('#deleteTranslationId').val();
    const languageId = $('input[name="language_id"]').val();

    // Butonu devre dışı bırak
    const btn = $(this);
    const originalText = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Siliniyor...');
    btn.prop('disabled', true);

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/translations/' + languageId + '/delete/' + id,
      type: 'DELETE',
      success: function (response) {
        if (response.success) {
          // Başarı mesajı
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showSuccess('Çeviri başarıyla silindi.', 'Başarılı!');
            // Modalı kapat
            $('#deleteConfirmModal').modal('hide');
            pencereYenile();
          } else {
            Swal.fire({
              title: 'Başarılı!',
              text: 'Çeviri başarıyla silindi.',
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              // Sayfayı yenile
              window.location.reload();
            });
          }
        } else {
          // Hata mesajı
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(response.error || 'Çeviri silinirken bir hata oluştu.', 'Hata!');
            // Modalı kapat
            $('#deleteConfirmModal').modal('hide');
          } else {
            Swal.fire({
              title: 'Hata!',
              text: response.error || 'Çeviri silinirken bir hata oluştu.',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false
            });
          }
        }
      },
      error: function (xhr) {
        let errorMessage = 'İşlem sırasında bir hata oluştu.';

        if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
        }

        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(errorMessage, 'Hata!');
        } else {
          Swal.fire({
            title: 'Hata!',
            text: errorMessage,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-danger'
            },
            buttonsStyling: false
          });
        }
      },
      complete: function () {
        // Butonu eski haline getir
        btn.html(originalText);
        btn.prop('disabled', false);
      }
    });
  });

  // Sayfalama işlemleri
  $(document).on('click', '.pagination .page-link', function (e) {
    e.preventDefault();

    // Sayfa değişiminde kayıt uyarısı
    if (formChanged) {
      if (!confirm('Kaydedilmemiş değişiklikler var. Devam etmek istiyor musunuz?')) {
        return false;
      }
    }

    const url = $(this).attr('href');
    if (!url) return false;

    // Sayfayı yükle
    window.location.href = url;
    return false;
  });

  // Sayfadan ayrılırken değişiklikler varsa uyar
  window.addEventListener('beforeunload', function (e) {
    if (formChanged) {
      // Modern tarayıcılar özel mesajları göstermiyor, sadece standart uyarı gösterecek
      const confirmationMessage = 'Kaydedilmemiş değişiklikleriniz var. Sayfadan ayrılmak istediğinize emin misiniz?';
      e.returnValue = confirmationMessage;
      return confirmationMessage;
    }
  });

  // Enter tuşuna basıldığında formu göndermeyi engelle
  $(document).on('keydown', '.translation-input', function (e) {
    if (e.keyCode === 13) {
      // Enter tuşu
      e.preventDefault();

      // Bir sonraki input'a geç
      const inputs = $('.translation-input');
      const currentIndex = inputs.index(this);
      const nextInput = inputs[currentIndex + 1];

      if (nextInput) {
        nextInput.focus();
      }

      return false;
    }
  });

  // Modal olayları
  $('#addTranslationModal').on('show.bs.modal', function () {
    // Formu sıfırla
    $('#addTranslationForm')[0].reset();
    $('#addTranslationForm .is-invalid').removeClass('is-invalid');
  });

  $('#addTranslationModal').on('hidden.bs.modal', function () {
    // Formu sıfırla
    $('#addTranslationForm')[0].reset();
    $('#addTranslationForm .is-invalid').removeClass('is-invalid');
  });

  $('#deleteConfirmModal').on('hidden.bs.modal', function () {
    // Değerleri temizle
    $('#deleteTranslationId').val('');
    $('#deleteTranslationKey').text('');
  });
});
