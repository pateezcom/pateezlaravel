/**
 * User Profile Form Validation - FormValidation implementation for user profile form
 * Uses Vuexy template with FormValidation library for professional form validation
 *
 * Kullanıcı Profil Form Doğrulama - FormValidation kütüphanesi kullanarak kullanıcı profil formu için profesyonel doğrulama
 */

'use strict';

// Toastr'ın tanımlı olmadığı durumlar için kontrol ve alternatif çözüm - artık AppHelpers.Messages kullanıyoruz
if (typeof AppHelpers === 'undefined' && typeof toastr === 'undefined') {
  // SweetAlert2 ile bildirimleri göster
  window.toastr = {
    success: function (message, title) {
      Swal.fire({
        icon: 'success',
        title: title || __('success'), // Dil dosyasından "Success"
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
        title: title || __('error'), // Dil dosyasından "Error"
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
  // Form tanımlamaları
  const formAccountSettings = document.getElementById('formAccountSettings');
  const formProfilePhoto = document.getElementById('formProfilePhoto');
  const formDeletePhoto = document.getElementById('formDeletePhoto');
  const formAccountDeactivation = document.getElementById('formAccountDeactivation');

  // Translation yüklendikten sonra işlemleri başlat
  window.addEventListener('translationsLoaded', function () {
    // Hesap Ayarları Formu Validasyonu
    if (formAccountSettings) {
      const formAccountSettingsValidation = FormValidation.formValidation(formAccountSettings, {
        fields: {
          // Ad Soyad validasyonu
          name: {
            validators: {
              notEmpty: {
                message: __('enter_full_name') // Dil dosyasından "Please enter your full name"
              },
              stringLength: {
                min: 3,
                max: 255,
                message: __('name_length_validation') // Dil dosyasından "Name must be between 3 and 255 characters"
              }
            }
          },
          // Kullanıcı Adı validasyonu
          username: {
            validators: {
              notEmpty: {
                message: __('enter_username') // Dil dosyasından "Please enter your username"
              },
              stringLength: {
                min: 3,
                max: 255,
                message: __('username_length_validation') // Dil dosyasından "Username must be between 3 and 255 characters"
              },
              regexp: {
                regexp: /^[a-zA-Z0-9_]+$/,
                message: __('username_format_validation') // Dil dosyasından "Username can only contain letters, numbers, and underscores"
              },
              remote: {
                url: baseUrl + 'admin/users/check-username',
                method: 'GET',
                data: function () {
                  return {
                    username: formAccountSettings.querySelector('[name="username"]').value,
                    id: formAccountSettings.dataset.userId
                  };
                },
                message: __('username_taken'), // Dil dosyasından "This username is already taken"
                async: false,
                display: 'dynamic',
                cache: false
              }
            }
          },
          // Slug validasyonu (isteğe bağlı)
          slug: {
            validators: {
              regexp: {
                regexp: /^[a-zA-Z0-9\-à-ÿ\s]+$/, // Türkçe karakter ve boşluk kullanımına izin ver
                message: __('slug_format_validation') // Dil dosyasından "Slug can only contain letters, numbers, hyphens, and spaces"
              },
              remote: {
                url: baseUrl + 'admin/users/check-slug',
                method: 'GET',
                data: function () {
                  return {
                    slug: formAccountSettings.querySelector('[name="slug"]').value,
                    id: formAccountSettings.dataset.userId
                  };
                },
                message: __('slug_taken'), // Dil dosyasından "This slug is already taken"
                async: false,
                display: 'dynamic',
                cache: false
              }
            }
          },
          // E-posta validasyonu
          email: {
            validators: {
              notEmpty: {
                message: __('enter_email') // Dil dosyasından "Please enter your email"
              },
              emailAddress: {
                message: __('enter_valid_email') // Dil dosyasından "Please enter a valid email address"
              },
              remote: {
                url: baseUrl + 'admin/users/check-email',
                method: 'GET',
                data: function () {
                  return {
                    email: formAccountSettings.querySelector('[name="email"]').value,
                    id: formAccountSettings.dataset.userId
                  };
                },
                message: __('email_taken'), // Dil dosyasından "This email is already taken"
                async: false,
                display: 'dynamic',
                cache: false
              }
            }
          },
          // Telefon validasyonu (isteğe bağlı)
          phone: {
            validators: {
              regexp: {
                regexp: /^\+[0-9]{1,4}[0-9]{6,15}$/,
                message: __('phone_format_validation') // "Lütfen geçerli bir telefon numarası girin (ülke kodu ile birlikte, +90xxxx)"
              }
            }
          },
          // Hakkımda validasyonu (isteğe bağlı)
          about_me: {
            validators: {
              stringLength: {
                max: 5000,
                message: __('about_me_length_validation') // Dil dosyasından "About me cannot exceed 5000 characters"
              }
            }
          },
          // Sosyal medya URL validasyonları
          facebook: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          twitter: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          instagram: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          tiktok: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          whatsapp: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          youtube: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          discord: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          telegram: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          pinterest: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          linkedin: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          twitch: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          vk: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
              }
            }
          },
          personal_website_url: {
            validators: {
              uri: {
                message: __('invalid_url') // Dil dosyasından "Please enter a valid URL"
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
            updateProfileInfo();
          });

          instance.on('core.form.invalid', function () {
            // Form geçersiz ise hata mesajı göster
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(__('form_validation_error'));
            } else if (typeof toastr !== 'undefined') {
              toastr.error(__('form_validation_error'));
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

      // Profil bilgisi güncelleme fonksiyonu
      function updateProfileInfo() {
        // Submit butonunu devre dışı bırak
        const submitBtn = formAccountSettings.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('saving'); // Dil dosyasından "Saving..."
        submitBtn.disabled = true;

        // Form verisini al
        const formData = new FormData(formAccountSettings);
        const userId = formAccountSettings.dataset.userId;

        // AJAX isteği gönder
        fetch(formAccountSettings.action, {
          method: 'POST', // Laravel route PUT için POST metodu kullanılır
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
                AppHelpers.Messages.showSuccess(data.message || __('profile_updated_successfully'));
              } else if (typeof toastr !== 'undefined') {
                toastr.success(data.message || __('profile_updated_successfully'), __('success'));
              } else {
                Swal.fire({
                  icon: 'success',
                  title: __('success'),
                  text: data.message || __('profile_updated_successfully'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            } else {
              // Hata mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(data.message || __('update_error'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(data.message || __('update_error'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: data.message || __('update_error'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(__('update_error'));
            } else if (typeof toastr !== 'undefined') {
              toastr.error(__('update_error'), __('error'));
            } else {
              Swal.fire({
                icon: 'error',
                title: __('error'),
                text: __('update_error'),
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
      formAccountSettings.addEventListener('submit', function (e) {
        e.preventDefault();
        formAccountSettingsValidation.validate();
      });
    }

    // Profil fotoğrafı validasyonu
    if (formProfilePhoto) {
      // Profil fotoğrafı yükleme butonunun dinleyicisi
      const photoInput = formProfilePhoto.querySelector('input[type="file"]');
      const submitButton = formProfilePhoto.querySelector('#submitProfilePhoto');

      if (photoInput) {
        photoInput.addEventListener('change', function (e) {
          const file = e.target.files[0];
          if (file) {
            // Dosya tipi kontrolü
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(__('invalid_image_type'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(__('invalid_image_type'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: __('invalid_image_type'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
              e.target.value = '';
              return;
            }

            // Dosya boyutu kontrolü (2MB)
            if (file.size > 2 * 1024 * 1024) {
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(__('image_too_large'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(__('image_too_large'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: __('image_too_large'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
              e.target.value = '';
              return;
            }

            // Form gönderme işlemi başlat
            submitButton.click();
          }
        });
      }

      // Form gönderimi
      formProfilePhoto.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = formProfilePhoto.querySelector('label');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('uploading'); // Dil dosyasından "Uploading..."
        submitBtn.disabled = true;

        // FormData oluştur
        const formData = new FormData(formProfilePhoto);

        // Add the file with the 'photo' name to make it compatible with Laravel Jetstream
        const fileInput = photoInput.files[0];
        if (fileInput) {
          formData.append('photo', fileInput);
        }

        // AJAX isteği gönder
        fetch(formProfilePhoto.action, {
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
              // Profil fotoğrafını güncelle
              const profileImage = document.getElementById('uploadedAvatar');
              if (profileImage && data.photo_url) {
                // Önbellek sorunlarını önlemek için timestamp ekle
                profileImage.src = data.photo_url + '?t=' + new Date().getTime();
              }

              // Başarı mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showSuccess(data.message || __('photo_updated_successfully'));
              } else if (typeof toastr !== 'undefined') {
                toastr.success(data.message || __('photo_updated_successfully'), __('success'));
              } else {
                Swal.fire({
                  icon: 'success',
                  title: __('success'),
                  text: data.message || __('photo_updated_successfully'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            } else {
              // Hata mesajı göster
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(data.message || __('update_error'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(data.message || __('update_error'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: data.message || __('update_error'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(__('update_error'));
            } else if (typeof toastr !== 'undefined') {
              toastr.error(__('update_error'), __('error'));
            } else {
              Swal.fire({
                icon: 'error',
                title: __('error'),
                text: __('update_error'),
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000
              });
            }
          })
          .finally(() => {
            // Submit butonunu tekrar etkinleştir
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;

            // Dosya input alanını sıfırla
            photoInput.value = '';
          });
      });
    }

    // Profil fotoğrafı silme onayı
    if (formDeletePhoto) {
      formDeletePhoto.addEventListener('submit', function (e) {
        e.preventDefault();

        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showConfirm({
            title: __('confirm_delete_photo'),
            text: __('confirm_delete_photo_text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: __('yes'),
            cancelButtonText: __('cancel'),
            customClass: {
              confirmButton: 'btn btn-primary me-3',
              cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              deleteProfilePhoto();
            }
          });
        } else {
          Swal.fire({
            title: __('confirm_delete_photo'),
            text: __('confirm_delete_photo_text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: __('yes'),
            cancelButtonText: __('cancel'),
            customClass: {
              confirmButton: 'btn btn-primary me-3',
              cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              deleteProfilePhoto();
            }
          });
        }

        function deleteProfilePhoto() {
          // Submit butonunu devre dışı bırak
          const submitBtn = formDeletePhoto.querySelector('button[type="submit"]');
          const originalHtml = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('deleting');
          submitBtn.disabled = true;

          // Form verilerini al
          const formData = new FormData(formDeletePhoto);

          // AJAX isteği gönder
          fetch(formDeletePhoto.action, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              Accept: 'application/json',
              'X-CSRF-TOKEN': formData.get('_token')
            },
            body: formData
          })
            .then(response => {
              if (!response.ok) {
                return response.json().then(errorData => Promise.reject(errorData));
              }
              return response.json();
            })
            .then(data => {
              if (data.success) {
                // Profil fotoğrafını varsayılana güncelle
                const profileImage = document.getElementById('uploadedAvatar');
                if (profileImage && data.photo_url) {
                  profileImage.src = data.photo_url + '?t=' + new Date().getTime();
                }

                // Başarı mesajı göster ve 1.5 saniye sonra sayfayı yenile
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showWithReload(
                    'success',
                    data.message || __('photo_deleted_successfully'),
                    __('success'),
                    1500
                  );
                } else if (typeof toastr !== 'undefined') {
                  toastr.options.onHidden = function () {
                    window.location.reload();
                  };
                  toastr.success(data.message || __('photo_deleted_successfully'), __('success'));
                } else {
                  Swal.fire({
                    icon: 'success',
                    title: __('success'),
                    text: data.message || __('photo_deleted_successfully'),
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: function () {
                      window.location.reload();
                    }
                  });
                }
              } else {
                // Hata mesajı göster
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showError(data.message || __('delete_error'));
                } else if (typeof toastr !== 'undefined') {
                  toastr.error(data.message || __('delete_error'), __('error'));
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: __('error'),
                    text: data.message || __('delete_error'),
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 3000
                  });
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(error.message || __('delete_error'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(error.message || __('delete_error'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: error.message || __('delete_error'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            })
            .finally(() => {
              // Submit butonunu tekrar etkinleştir
              submitBtn.innerHTML = originalHtml;
              submitBtn.disabled = false;
            });
        }
      });
    }

    // Hesap silme onayı
    if (formAccountDeactivation) {
      // Hesap silme formunu dinle
      formAccountDeactivation.addEventListener('submit', function (e) {
        e.preventDefault();

        // Onay kutusunu kontrol et
        if (!document.getElementById('confirmAccountDeleteCheckbox').checked) {
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(__('confirm_deletion_checkbox'));
          } else {
            toastr.error(__('confirm_deletion_checkbox'), __('error'));
          }
          return;
        }

        // Onay penceresi göster
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showConfirm({
            title: __('are_you_sure'),
            text: __('action_cannot_be_undone'),
            icon: 'warning',
            confirmButtonText: __('yes_delete'),
            cancelButtonText: __('cancel'),
            customClass: {
              confirmButton: 'btn btn-danger me-3',
              cancelButton: 'btn btn-secondary'
            }
          }).then(function (result) {
            if (result.isConfirmed) {
              // AJAX isteği gönder
              deleteAccount();
            }
          });
        } else {
          Swal.fire({
            title: __('are_you_sure'),
            text: __('action_cannot_be_undone'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: __('yes_delete'),
            cancelButtonText: __('cancel'),
            customClass: {
              confirmButton: 'btn btn-danger me-3',
              cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              // AJAX isteği gönder
              deleteAccount();
            }
          });
        }

        function deleteAccount() {
          // Submit butonunu devre dışı bırak
          const submitBtn = formAccountDeactivation.querySelector('button[type="submit"]');
          const originalHtml = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>' + __('deleting');
          submitBtn.disabled = true;

          // Form verilerini al
          const formData = new FormData(formAccountDeactivation);

          // AJAX isteği gönder
          fetch(formAccountDeactivation.action, {
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
                // Başarı mesajı göster ve kullanıcı listesine yönlendir
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showWithRedirect(
                    'success',
                    data.message || __('account_deleted_successfully'),
                    __('success'),
                    baseUrl + 'admin/users'
                  );
                } else if (typeof toastr !== 'undefined') {
                  toastr.options.onHidden = function () {
                    window.location.href = baseUrl + 'admin/users';
                  };
                  toastr.success(data.message || __('account_deleted_successfully'), __('success'));
                } else {
                  Swal.fire({
                    icon: 'success',
                    title: __('success'),
                    text: data.message || __('account_deleted_successfully'),
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: function () {
                      window.location.href = baseUrl + 'admin/users';
                    }
                  });
                }
              } else {
                // Hata mesajı göster
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showError(data.message || __('delete_error'));
                } else if (typeof toastr !== 'undefined') {
                  toastr.error(data.message || __('delete_error'), __('error'));
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: __('error'),
                    text: data.message || __('delete_error'),
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 3000
                  });
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(__('delete_error'));
              } else if (typeof toastr !== 'undefined') {
                toastr.error(__('delete_error'), __('error'));
              } else {
                Swal.fire({
                  icon: 'error',
                  title: __('error'),
                  text: __('delete_error'),
                  toast: true,
                  position: 'bottom',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            })
            .finally(() => {
              // Submit butonunu tekrar etkinleştir
              submitBtn.innerHTML = originalHtml;
              submitBtn.disabled = false;
            });
        }
      });
    }
  });

  // Hesap Detayları bölümünde kullanıcı adı değiştiğinde slug otomatik oluştur
  if (formAccountSettings) {
    const usernameInput = formAccountSettings.querySelector('#username');
    const slugInput = formAccountSettings.querySelector('#slug');

    if (usernameInput && slugInput) {
      usernameInput.addEventListener('input', function (e) {
        // Eğer slug alanı boşsa veya daha önce değiştirilmemişse
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
          // Kullanıcı adından slug oluştur
          let titleStr = e.target.value;

          // Türkçe karakterleri ve özel karakterleri düzenle
          const replacements = {
            ç: 'c',
            Ç: 'C',
            ğ: 'g',
            Ğ: 'G',
            ı: 'i',
            İ: 'I',
            ö: 'o',
            Ö: 'O',
            ş: 's',
            Ş: 'S',
            ü: 'u',
            Ü: 'U',
            ' ': '-' // Boşlukları tire ile değiştir
          };

          let slug = titleStr;
          // Türkçe ve özel karakterleri değiştir
          Object.keys(replacements).forEach(find => {
            slug = slug.replace(new RegExp(find, 'g'), replacements[find]);
          });

          // Sadece izin verilen karakterleri bırak (alfanümerik ve tire)
          slug = slug.replace(/[^a-zA-Z0-9\-]/g, '');
          // Birden fazla tireyi tek tire yap
          slug = slug.replace(/\-\-+/g, '-');
          // Baştaki ve sondaki tireleri kaldır
          slug = slug.replace(/^-+/, '').replace(/-+$/, '');
          // Küçük harfe çevir
          slug = slug.toLowerCase();

          // Eğer slug boşsa (sadece özel karakterler içeriyorsa)
          if (!slug) {
            slug = 'user-' + Date.now(); // Varsayılan slug oluştur
          }

          slugInput.value = slug;
          slugInput.dataset.autoGenerated = 'true';
        }
      });

      // Slug alanı manuel değiştirildiğinde otomatik oluşturmayı devre dışı bırak
      slugInput.addEventListener('input', function () {
        slugInput.dataset.autoGenerated = 'false';
      });
    }
  }

  // Telefon alanı için maskeleme
  if (formAccountSettings) {
    const phoneInput = formAccountSettings.querySelector('#phone');
    if (phoneInput) {
      // Telefon alanı için maskeleme
      new Cleave(phoneInput, {
        blocks: [3, 1, 4, 4, 4],
        delimiters: [' ', ' ', ' ', ' '],
        numericOnly: true
      });
    }
  }
});
