/**
 * User Profile Security Form Validation - FormValidation implementation for password update form
 * Uses Vuexy template with FormValidation library for professional form validation
 *
 * Kullanıcı Profili Güvenlik Form Doğrulama - FormValidation kütüphanesi kullanarak şifre güncelleme formu için profesyonel doğrulama
 */

'use strict';

// Toastr'ın tanımlı olmadığı durumlar için kontrol ve alternatif çözüm - artık AppHelpers.Messages kullanıyoruz
if (typeof AppHelpers === 'undefined' && typeof toastr === 'undefined') {
  // SweetAlert2 ile bildirimleri göster
  window.toastr = {
    success: function (message, title) {
      Swal.fire({
        icon: 'success',
        title: title || __('success'),
        text: message,
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 3000
      });
    },
    error: function (message, title) {
      Swal.fire({
        icon: 'error',
        title: title || __('error'),
        text: message,
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 3000
      });
    },
    options: {
      positionClass: 'toast-bottom-center'
    }
  };
}

// toastr varsa pozisyonunu ayarla
if (typeof toastr !== 'undefined') {
  toastr.options = toastr.options || {};
  toastr.options.positionClass = 'toast-bottom-center';
}

// Initialize form validation on document ready
document.addEventListener('DOMContentLoaded', function () {
  // Form tanımlaması
  const formSecuritySettings = document.getElementById('formSecuritySettings');

  // Translation yüklendikten sonra işlemleri başlat
  window.addEventListener('translationsLoaded', function () {
    // Güvenlik Ayarları Formu Validasyonu
    if (formSecuritySettings) {
      const formSecurityValidation = FormValidation.formValidation(formSecuritySettings, {
        fields: {
          // Mevcut Şifre validasyonu
          current_password: {
            validators: {
              notEmpty: {
                message: __('current_password_required')
              }
            }
          },
          // Yeni Şifre validasyonu
          password: {
            validators: {
              notEmpty: {
                message: __('new_password_required')
              },
              stringLength: {
                min: 4,
                message: __('password_length_validation')
              }
            }
          },
          // Şifre Onayı validasyonu
          password_confirmation: {
            validators: {
              notEmpty: {
                message: __('confirm_password_required')
              },
              identical: {
                compare: function () {
                  return formSecuritySettings.querySelector('[name="password"]').value;
                },
                message: __('password_mismatch')
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            eleInvalidClass: 'is-invalid',
            rowSelector: '.mb-3'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('core.form.valid', function () {
            // Form geçerli olduğunda güncelleme işlemi başlat
            updatePassword();
          });

          instance.on('core.form.invalid', function () {
            // Form geçersiz ise hata mesajı göster
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(__('form_validation_error'));
            } else if (typeof toastr !== 'undefined') {
              toastr.error(__('form_validation_error'), __('error'));
            } else {
              Swal.fire({
                icon: 'error',
                title: __('error'),
                text: __('form_validation_error'),
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000
              });
            }
          });
        }
      });

      // Şifre güncelleme fonksiyonu
      function updatePassword() {
        // Submit butonunu devre dışı bırak
        const submitBtn = formSecuritySettings.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('saving');
        submitBtn.disabled = true;

        // Form verisini al
        const formData = new FormData(formSecuritySettings);
        const userId = formSecuritySettings.dataset.userId;

        // AJAX isteği gönder
        fetch(formSecuritySettings.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            'X-CSRF-TOKEN': formData.get('_token')
          },
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Başarı mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showSuccess(data.message || __('password_updated_successfully'));
              } else if (typeof toastr !== 'undefined') {
                toastr.success(data.message || __('password_updated_successfully'), __('success'));
              } else {
                Swal.fire({
                  icon: 'success',
                  title: __('success'),
                  text: data.message || __('password_updated_successfully'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }

              // Formu sıfırla
              formSecuritySettings.reset();
            } else {
              // Validation hataları varsa form alanlarında göster
              if (data.errors) {
                if (data.errors.current_password) {
                  if (typeof AppHelpers !== 'undefined') {
                    AppHelpers.Messages.showError(data.errors.current_password[0]);
                  } else if (typeof toastr !== 'undefined') {
                    toastr.error(data.errors.current_password[0], __('error'));
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: __('error'),
                      text: data.errors.current_password[0],
                      toast: true,
                      position: 'bottom',
                      showConfirmButton: false,
                      timer: 3000
                    });
                  }
                } else {
                  // Genel hata mesajı göster
                  if (typeof AppHelpers !== 'undefined') {
                    AppHelpers.Messages.showError(data.message || __('password_update_error'));
                  } else if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || __('password_update_error'), __('error'));
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: __('error'),
                      text: data.message || __('password_update_error'),
                      toast: true,
                      position: 'bottom',
                      showConfirmButton: false,
                      timer: 3000
                    });
                  }
                }
              } else {
                // Genel hata mesajı göster
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showError(data.message || __('password_update_error'));
                } else if (typeof toastr !== 'undefined') {
                  toastr.error(data.message || __('password_update_error'), __('error'));
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: __('error'),
                    text: data.message || __('password_update_error'),
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 3000
                  });
                }
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            // Hata mesajı göster
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(__('password_update_error'));
            } else if (typeof toastr !== 'undefined') {
              toastr.error(__('password_update_error'), __('error'));
            } else {
              Swal.fire({
                icon: 'error',
                title: __('error'),
                text: __('password_update_error'),
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000
              });
            }
          })
          .finally(() => {
            // Submit butonunu tekrar etkinleştir
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          });
      }

      // Form submit olayını dinle
      formSecuritySettings.addEventListener('submit', function (e) {
        e.preventDefault();
        formSecurityValidation.validate();
      });
    }
  });

  // Şifre göster/gizle işlevselliği için event listener
  document.querySelectorAll('.form-password-toggle .input-group-text').forEach(toggleButton => {
    toggleButton.addEventListener('click', e => {
      const input = e.currentTarget.parentNode.querySelector('input');
      const icon = e.currentTarget.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
      } else {
        input.type = 'password';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
      }
    });
  });
});
