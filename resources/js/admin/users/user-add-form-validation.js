/**
 * User Add Form Validation - FormValidation implementation for user add form
 * Uses Vuexy template with FormValidation library for professional form validation
 *
 * Kullanıcı Ekleme Form Doğrulama - FormValidation kütüphanesi kullanarak kullanıcı ekleme formu için profesyonel doğrulama
 */

'use strict';

// Initialize form validation on document ready
document.addEventListener('DOMContentLoaded', function () {
  // Add User Form Validation
  const addUserForm = document.getElementById('addUserForm');

  // Only initialize if form exists
  window.addEventListener('translationsLoaded', function () {
    if (addUserForm) {
      // Initialize FormValidation
      const addUserFormValidation = FormValidation.formValidation(addUserForm, {
        fields: {
          // Full Name validation
          name: {
            validators: {
              notEmpty: {
                message: __('enter_full_name')
              },
              stringLength: {
                min: 3,
                max: 255,
                message: __('name_length_validation')
              }
            }
          },
          // Username validation
          username: {
            validators: {
              notEmpty: {
                message: __('enter_username')
              },
              stringLength: {
                min: 3,
                max: 255,
                message: __('username_length_validation')
              },
              regexp: {
                regexp: /^[a-zA-Z0-9_]+$/,
                message: __('username_format_validation')
              },
              remote: {
                url: baseUrl + 'admin/users/check-username',
                method: 'GET',
                data: function () {
                  return {
                    username: addUserForm.querySelector('[name="username"]').value
                  };
                },
                message: __('username_taken'),
                async: false,
                display: 'dynamic', // Control when the feedback is shown
                cache: false // Don't cache the remote response
              }
            }
          },
          // Email validation
          email: {
            validators: {
              notEmpty: {
                message: __('enter_email')
              },
              emailAddress: {
                message: __('enter_valid_email')
              },
              remote: {
                url: baseUrl + 'admin/users/check-email',
                method: 'GET',
                data: function () {
                  return {
                    email: addUserForm.querySelector('[name="email"]').value
                  };
                },
                message: __('email_taken'),
                async: false,
                display: 'dynamic', // Control when the feedback is shown
                cache: false // Don't cache the remote response
              }
            }
          },
          // Role validation
          role_id: {
            validators: {
              notEmpty: {
                message: __('select_role')
              }
            }
          },
          // Password validation
          password: {
            validators: {
              notEmpty: {
                message: __('enter_password')
              },
              stringLength: {
                min: 4,
                message: __('password_length_validation')
              }
            }
          },
          // Password confirmation validation
          password_confirmation: {
            validators: {
              notEmpty: {
                message: __('confirm_password')
              },
              identical: {
                compare: function () {
                  return addUserForm.querySelector('[name="password"]').value;
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
            // Validation mesajlarını gösterme seviyeleri
            eleInvalidClass: 'is-invalid',
            message: false, // Mesajları gösterme
            rowSelector: function (field, ele) {
              // field is the field name & ele is the field element
              switch (field) {
                case 'name':
                  return '.col-12.col-md-12';
                case 'username':
                case 'email':
                case 'role_id':
                case 'password':
                case 'password_confirmation':
                  return '.col-12.col-md-6';
                default:
                  return '.row';
              }
            }
          }),
          // Submit button handler with custom AJAX submission
          submitButton: new FormValidation.plugins.SubmitButton(),
          // Auto focus on error fields
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          // Hata mesajlarını gösterme - düşük profilli validasyon için
          instance.on('core.form.valid', function () {
            // Form geçerli olduğunda kaydedelim
            submitForm();
          });

          instance.on('core.form.invalid', function () {
            // Form geçersiz olduğunda sessizce işlemi iptal edelim
            console.log('Form validation failed');
          });
        }
      });

      // Submit form function
      function submitForm() {
        // Disable submit button
        const submitBtn = addUserForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('saving');
        submitBtn.disabled = true;

        // Create form data
        const formData = new FormData(addUserForm);

        // Checkbox için özel işlem - işaretlenmediyse '0' olarak gönder
        if (!formData.has('reward_system_active')) {
          formData.append('reward_system_active', '0');
        } else {
          // Checkbox işaretliyse '1' olarak gönder
          formData.set('reward_system_active', '1');
        }

        // Convert FormData to URLSearchParams for AJAX
        const params = new URLSearchParams();
        for (const pair of formData) {
          params.append(pair[0], pair[1]);
        }

        // AJAX request
        fetch(baseUrl + 'admin/users', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': formData.get('_token')
          },
          body: params
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Hide modal
              const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
              modal.hide();

              // Show success message
              toastr.success(data.message || __('user_added_successfully'), __('success'));

              // Reload DataTable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Show error message
              if (data.message) {
                toastr.error(data.message, __('error'));
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            toastr.error(__('save_error'), __('error'));
          })
          .finally(() => {
            // Re-enable submit button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          });
      }

      // Handle form submission
      addUserForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validate the form
        addUserFormValidation.validate();
      });

      // Handle modal hidden event to reset form
      const addUserModal = document.getElementById('addUserModal');
      if (addUserModal) {
        addUserModal.addEventListener('hidden.bs.modal', function () {
          // Reset form
          addUserForm.reset();
          // Reset FormValidation
          addUserFormValidation.resetForm();
        });
      }

      // Password visibility toggle
      const passwordToggles = document.querySelectorAll('.input-group-text');
      if (passwordToggles) {
        passwordToggles.forEach(toggle => {
          toggle.addEventListener('click', function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

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
      }
    }
  });

  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
