/**
 * Form Validation for User Management
 * Uses FormValidation.io for client-side validation with custom rules
 * 
 * Kullanıcı Yönetimi için Form Doğrulama
 * FormValidation.io kullanarak müşteri tarafında doğrulama yapar
 */

"use strict";

// Document ready
document.addEventListener('DOMContentLoaded', function() {
  const addNewUserForm = document.getElementById('addNewUserForm');
  const editUserForm = document.getElementById('editUserForm');
  const editPermissionForm = document.getElementById('editPermissionForm');
  
  // Common validations
  const nameValidators = {
    validators: {
      notEmpty: {
        message: 'Ad soyad alanı zorunludur'
      },
      stringLength: {
        min: 3,
        max: 50,
        message: 'Ad soyad 3-50 karakter arasında olmalıdır'
      }
    }
  };
  
  const usernameValidators = {
    validators: {
      notEmpty: {
        message: 'Kullanıcı adı zorunludur'
      },
      stringLength: {
        min: 3,
        max: 25,
        message: 'Kullanıcı adı 3-25 karakter arasında olmalıdır'
      },
      regexp: {
        regexp: /^[a-zA-Z0-9_\-\.]+$/,
        message: 'Kullanıcı adı yalnızca harf, sayı, alt çizgi, nokta ve tire içerebilir'
      },
      remote: {
        url: baseUrl + 'admin/users/check-username',
        data: function() {
          return {
            username: addNewUserForm ? 
              document.getElementById('add-user-username').value : 
              document.getElementById('edit-user-username').value,
            userId: editUserForm ? 
              document.getElementById('edit-user-id').value : ''
          };
        },
        message: 'Bu kullanıcı adı zaten kullanılıyor'
      }
    }
  };
  
  const emailValidators = {
    validators: {
      notEmpty: {
        message: 'E-posta adresi zorunludur'
      },
      emailAddress: {
        message: 'Geçerli bir e-posta adresi giriniz'
      },
      remote: {
        url: baseUrl + 'admin/users/check-email',
        data: function() {
          return {
            email: addNewUserForm ? 
              document.getElementById('add-user-email').value : 
              document.getElementById('edit-user-email').value,
            userId: editUserForm ? 
              document.getElementById('edit-user-id').value : ''
          };
        },
        message: 'Bu e-posta adresi zaten kullanılıyor'
      }
    }
  };
  
  const roleValidators = {
    validators: {
      notEmpty: {
        message: 'Lütfen bir rol seçin'
      }
    }
  };
  
  // Add New User Form Validation
  if (addNewUserForm) {
    const fvAddUser = FormValidation.formValidation(addNewUserForm, {
      fields: {
        userFullname: nameValidators,
        userUsername: usernameValidators,
        userEmail: emailValidators,
        userRole: roleValidators,
        userPassword: {
          validators: {
            notEmpty: {
              message: 'Şifre zorunludur'
            },
            stringLength: {
              min: 8,
              message: 'Şifre en az 8 karakter olmalıdır'
            }
          }
        },
        confirmPassword: {
          validators: {
            notEmpty: {
              message: 'Şifre tekrarı zorunludur'
            },
            identical: {
              compare: function() {
                return addNewUserForm.querySelector('[name="userPassword"]').value;
              },
              message: 'Şifreler eşleşmiyor'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.mb-4'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('core.form.valid', function() {
          // Clear any previous errors
          const errorDiv = document.getElementById('add-form-errors');
          errorDiv.classList.add('d-none');
          
          // Submit form via AJAX
          const formData = new FormData(addNewUserForm);
          
          $.ajax({
            url: baseUrl + 'admin/users',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              if (response.success) {
                // Show success message
                toastr.success('Kullanıcı başarıyla eklendi');
                
                // Close modal and refresh table
                $('#addUserModal').modal('hide');
                $('.datatables-users').DataTable().ajax.reload();
                
                // Reset form
                addNewUserForm.reset();
                instance.resetForm();
              } else {
                // Show error message
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = response.message || 'İşlem sırasında hata oluştu';
              }
            },
            error: function(xhr) {
              // Handle validation errors
              if (xhr.status === 422 && xhr.responseJSON) {
                const errors = xhr.responseJSON.errors;
                let errorMsg = '<ul class="mb-0">';
                
                for (const field in errors) {
                  if (errors.hasOwnProperty(field)) {
                    errorMsg += `<li>${errors[field][0]}</li>`;
                  }
                }
                
                errorMsg += '</ul>';
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = errorMsg;
              } else {
                // Show generic error
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = 'İşlem sırasında hata oluştu';
              }
            }
          });
          
          // Prevent default form submission
          return false;
        });
      }
    });
    
    // Username availability check on blur
    document.getElementById('add-user-username').addEventListener('blur', function() {
      const username = this.value;
      if (username.length >= 3) {
        $.ajax({
          url: baseUrl + 'admin/users/check-username',
          type: 'GET',
          data: { username: username },
          success: function(response) {
            const availabilityDiv = document.getElementById('username-availability-add');
            if (response.available) {
              availabilityDiv.innerHTML = '<small class="text-success">Bu kullanıcı adı kullanılabilir</small>';
            } else {
              availabilityDiv.innerHTML = '<small class="text-danger">Bu kullanıcı adı zaten kullanılıyor</small>';
            }
          }
        });
      }
    });
  }
  
  // Edit User Form Validation
  if (editUserForm) {
    const fvEditUser = FormValidation.formValidation(editUserForm, {
      fields: {
        editUserFullname: nameValidators,
        editUserUsername: usernameValidators,
        editUserEmail: emailValidators,
        editUserRole: roleValidators,
        editUserPassword: {
          validators: {
            stringLength: {
              min: 8,
              message: 'Şifre en az 8 karakter olmalıdır'
            }
          }
        },
        editUserConfirmPassword: {
          validators: {
            identical: {
              compare: function() {
                return editUserForm.querySelector('[name="editUserPassword"]').value;
              },
              message: 'Şifreler eşleşmiyor'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.mb-4'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('core.form.valid', function() {
          // Clear any previous errors
          const errorDiv = document.getElementById('edit-form-errors');
          errorDiv.classList.add('d-none');
          
          // Get user ID
          const userId = document.getElementById('edit-user-id').value;
          
          // Submit form via AJAX
          const formData = new FormData(editUserForm);
          formData.append('_method', 'PUT'); // For Laravel method spoofing
          
          $.ajax({
            url: baseUrl + 'admin/users/' + userId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              if (response.success) {
                // Show success message
                toastr.success('Kullanıcı başarıyla güncellendi');
                
                // Close modal and refresh table
                $('#editUserModal').modal('hide');
                $('.datatables-users').DataTable().ajax.reload();
              } else {
                // Show error message
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = response.message || 'İşlem sırasında hata oluştu';
              }
            },
            error: function(xhr) {
              // Handle validation errors
              if (xhr.status === 422 && xhr.responseJSON) {
                const errors = xhr.responseJSON.errors;
                let errorMsg = '<ul class="mb-0">';
                
                for (const field in errors) {
                  if (errors.hasOwnProperty(field)) {
                    errorMsg += `<li>${errors[field][0]}</li>`;
                  }
                }
                
                errorMsg += '</ul>';
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = errorMsg;
              } else {
                // Show generic error
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = 'İşlem sırasında hata oluştu';
              }
            }
          });
          
          // Prevent default form submission
          return false;
        });
      }
    });
    
    // Username availability check on blur
    document.getElementById('edit-user-username').addEventListener('blur', function() {
      const username = this.value;
      const userId = document.getElementById('edit-user-id').value;
      
      if (username.length >= 3) {
        $.ajax({
          url: baseUrl + 'admin/users/check-username',
          type: 'GET',
          data: { 
            username: username,
            userId: userId 
          },
          success: function(response) {
            const availabilityDiv = document.getElementById('username-availability');
            if (response.available) {
              availabilityDiv.innerHTML = '<small class="text-success">Bu kullanıcı adı kullanılabilir</small>';
            } else {
              availabilityDiv.innerHTML = '<small class="text-danger">Bu kullanıcı adı zaten kullanılıyor</small>';
            }
          }
        });
      }
    });
    
    // Handle modal open to load user data
    document.addEventListener('click', function(e) {
      const editBtn = e.target.closest('.edit-record');
      if (editBtn) {
        const userId = editBtn.getAttribute('data-id');
        
        // Fetch user data
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/edit',
          type: 'GET',
          success: function(response) {
            if (response.success) {
              const user = response.data;
              
              // Fill form with user data
              document.getElementById('edit-user-id').value = user.id;
              document.getElementById('edit-user-fullname').value = user.name;
              document.getElementById('edit-user-username').value = user.username;
              document.getElementById('edit-user-email').value = user.email;
              document.getElementById('edit-user-role').value = user.role_id || '';
              document.getElementById('edit-user-status').value = user.status || 2;
              document.getElementById('edit-user-reward').checked = user.reward_system_active;
              
              // Clear password fields
              document.getElementById('edit-user-password').value = '';
              document.getElementById('edit-user-confirm-password').value = '';
              
              // Clear validation
              fvEditUser.resetForm();
              
              // Show modal
              $('#editUserModal').modal('show');
            } else {
              toastr.error('Kullanıcı bilgileri yüklenemedi');
            }
          },
          error: function() {
            toastr.error('Kullanıcı bilgileri yüklenemedi');
          }
        });
      }
    });
  }
  
  // Permission Management Form
  if (editPermissionForm) {
    const fvPermissions = FormValidation.formValidation(editPermissionForm, {
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('core.form.valid', function() {
          // Clear any previous errors
          const errorDiv = document.getElementById('permission-form-errors');
          errorDiv.classList.add('d-none');
          
          // Get user ID
          const userId = document.getElementById('permission-user-id').value;
          
          // Submit form via AJAX
          const formData = new FormData(editPermissionForm);
          
          $.ajax({
            url: baseUrl + 'admin/users/' + userId + '/permissions',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              if (response.success) {
                // Show success message
                toastr.success('Kullanıcı izinleri başarıyla güncellendi');
                
                // Close modal
                $('#editPermissionModal').modal('hide');
              } else {
                // Show error message
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = response.message || 'İşlem sırasında hata oluştu';
              }
            },
            error: function(xhr) {
              // Handle validation errors
              if (xhr.status === 422 && xhr.responseJSON) {
                const errors = xhr.responseJSON.errors;
                let errorMsg = '<ul class="mb-0">';
                
                for (const field in errors) {
                  if (errors.hasOwnProperty(field)) {
                    errorMsg += `<li>${errors[field][0]}</li>`;
                  }
                }
                
                errorMsg += '</ul>';
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = errorMsg;
              } else {
                // Show generic error
                errorDiv.classList.remove('d-none');
                errorDiv.querySelector('.alert-body').innerHTML = 'İşlem sırasında hata oluştu';
              }
            }
          });
          
          // Prevent default form submission
          return false;
        });
      }
    });
    
    // Handle select all checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
      selectAll.addEventListener('change', function() {
        const checked = this.checked;
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        
        checkboxes.forEach(function(checkbox) {
          checkbox.checked = checked;
        });
      });
    }
    
    // Handle permission modal open
    document.addEventListener('click', function(e) {
      const permBtn = e.target.closest('.permission-record');
      if (permBtn) {
        const userId = permBtn.getAttribute('data-id');
        
        // Fetch user permissions
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/permissions/edit',
          type: 'GET',
          success: function(response) {
            if (response.success) {
              // Set user ID
              document.getElementById('permission-user-id').value = userId;
              
              // Reset all checkboxes
              const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
              checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
              });
              document.getElementById('selectAll').checked = false;
              
              // Check user permissions
              const permissions = response.data;
              permissions.forEach(function(permission) {
                const checkbox = document.querySelector(`input[name="permissions[]"][value="${permission}"]`);
                if (checkbox) {
                  checkbox.checked = true;
                }
              });
              
              // Show modal
              $('#editPermissionModal').modal('show');
            } else {
              toastr.error('Kullanıcı izinleri yüklenemedi');
            }
          },
          error: function() {
            toastr.error('Kullanıcı izinleri yüklenemedi');
          }
        });
      }
    });
  }
  
  // Delete User Confirmation
  document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.delete-record');
    if (deleteBtn) {
      const userId = deleteBtn.getAttribute('data-id');
      
      // Confirm delete
      Swal.fire({
        title: 'Emin misiniz?',
        text: 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal',
        customClass: {
          confirmButton: 'btn btn-danger',
          cancelButton: 'btn btn-secondary ms-3'
        },
        buttonsStyling: false
      }).then(function(result) {
        if (result.isConfirmed) {
          // Delete user
          $.ajax({
            url: baseUrl + 'admin/users/' + userId,
            type: 'POST',
            data: {
              _method: 'DELETE',
              _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(response) {
              if (response.success) {
                // Show success message
                toastr.success('Kullanıcı başarıyla silindi');
                
                // Refresh table
                $('.datatables-users').DataTable().ajax.reload();
              } else {
                toastr.error(response.message || 'Kullanıcı silinemedi');
              }
            },
            error: function() {
              toastr.error('Kullanıcı silinemedi');
            }
          });
        }
      });
    }
  });
});
