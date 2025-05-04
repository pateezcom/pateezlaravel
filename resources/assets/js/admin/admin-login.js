/**
 *  Admin Login
 */

'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Hata mesajlarını temizleme
    const loginForm = document.getElementById('formAuthentication');
    if (loginForm) {
      const inputs = loginForm.querySelectorAll('input');
      const errorMessages = document.querySelectorAll('.invalid-feedback');

      inputs.forEach(input => {
        input.addEventListener('input', () => {
          // Input değiştiğinde hata mesajlarını gizle
          errorMessages.forEach(error => {
            error.style.display = 'none';
          });

          // Invalid class'ını kaldır
          inputs.forEach(inp => {
            inp.classList.remove('is-invalid');
          });
        });
      });
    }

    // Form validation for Login
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          email: {
            validators: {
              notEmpty: {
                message: 'Lütfen e-posta veya kullanıcı adınızı girin'
              },
              stringLength: {
                min: 4,
                message: 'Kullanıcı adı en az 4 karakter olmalıdır'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Lütfen şifrenizi girin'
              },
              stringLength: {
                min: 4,
                message: 'Şifre en az 4 karakter olmalıdır'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-6'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});
