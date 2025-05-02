/**
 * User Profile Security Form Validation - FormValidation implementation for password update form
 * Uses Vuexy template with FormValidation library for professional form validation
 *
 * Kullanıcı Profili Güvenlik Form Doğrulama - FormValidation kütüphanesi kullanarak şifre güncelleme formu için profesyonel doğrulama
 */

'use strict';

// Toastr'ın tanımlı olmadığı durumlar için kontrol ve alternatif çözüm
if (typeof toastr === 'undefined') {
  // SweetAlert2 ile bildirimleri göster
  window.toastr = {
    success: function (message) {
      Swal.fire({
        icon: 'success',
        title: __('success'),
        text: message,
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 3000
      });
    },
    error: function (message) {
      Swal.fire({
        icon: 'error',
        title: __('error'),
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

// Initialize form validation on document ready
document.addEventListener('DOMContentLoaded', function () {
  // Form tanımlamaları
  const formChangePassword = document.getElementById('formChangePassword');

  // Translation yüklendikten sonra işlemleri başlat
  window.addEventListener('translationsLoaded', function () {
    // Şifre Değiştirme Formu Validasyonu
    if (formChangePassword) {
      const formChangePasswordValidation = FormValidation.formValidation(formChangePassword, {
        fields: {
          // Mevcut şifre validasyonu
          current_password: {
            validators: {
              notEmpty: {
                message: __('enter_current_password')
              },
              stringLength: {
                min: 4,
                message: __('password_length_validation')
              }
            }
          },
          // Yeni şifre validasyonu
          password: {
            validators: {
              notEmpty: {
                message: __('enter_new_password')
              },
              stringLength: {
                min: 4,
                message: __('password_length_validation')
              }
            }
          },
          // Şifre onayı validasyonu
          password_confirmation: {
            validators: {
              notEmpty: {
                message: __('confirm_password')
              },
              identical: {
                compare: function () {
                  return formChangePassword.querySelector('[name="password"]').value;
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
            // Form geçerli olduğunda şifre güncelleme işlemi başlat
            updatePassword();
          });

          instance.on('core.form.invalid', function () {
            // Form geçersiz ise hata mesajı göster
            toastr.error(__('form_validation_error'));
          });
        }
      });

      // Şifre güncelleme fonksiyonu
      function updatePassword() {
        // Submit butonunu devre dışı bırak
        const submitBtn = formChangePassword.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('saving');
        submitBtn.disabled = true;

        // Form verisini al
        const formData = new FormData(formChangePassword);

        // AJAX isteği gönder
        fetch(formChangePassword.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': formData.get('_token')
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Başarı mesajı göster
            toastr.success(data.message || __('password_updated_successfully'));
            // Formu sıfırla
            formChangePassword.reset();
          } else {
            // Hata mesajı göster
            if (data.errors) {
              // Belirli hata mesajlarını göster
              Object.keys(data.errors).forEach(field => {
                toastr.error(data.errors[field][0]);
              });
            } else {
              // Genel hata mesajı göster
              toastr.error(data.message || __('update_error'));
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          // Hata mesajı göster
          toastr.error(__('update_error'));
        })
        .finally(() => {
          // Submit butonunu tekrar etkinleştir
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        });
      }

      // Form submit olayını dinle
      formChangePassword.addEventListener('submit', function (e) {
        e.preventDefault();
        formChangePasswordValidation.validate();
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

// Toastr Yapılandırması
toastr.options = {
  closeButton: true,
  newestOnTop: false,
  progressBar: true,
  positionClass: 'toast-bottom-center',
  preventDuplicates: false,
  onclick: null,
  showDuration: '300',
  hideDuration: '1000',
  timeOut: '5000',
  extendedTimeOut: '1000',
  showEasing: 'swing',
  hideEasing: 'linear',
  showMethod: 'fadeIn',
  hideMethod: 'fadeOut'
};