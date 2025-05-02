/**
 * Role Permissions Form Validation
 * FormValidation implementation for role and permissions management
 *
 * Rol ve İzinler Form Doğrulaması
 * Rol ve izin yönetimi için FormValidation implementasyonu
 */

'use strict';

// DOM içeriği yüklendiğinde çalıştır
document.addEventListener('DOMContentLoaded', function () {
  // Toast konfigürasyonu - Vuexy Template standardı
  const toastOptions = {
    closeButton: true,
    newestOnTop: true,
    progressBar: true,
    positionClass: 'toast-top-right',
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

  // Modal ve form elementleri
  const addRoleModal = document.getElementById('addRoleModal');
  const addRoleForm = document.getElementById('addRoleForm');
  
  // Form öğeleri
  let roleId, roleName, selectAllCheckbox;
  
  // Form Validation'ı başlatmadan önce elementlerin var olduğundan emin olalım
  if (addRoleModal && addRoleForm) {
    // Form öğeleri
    roleId = document.getElementById('roleId');
    roleName = document.getElementById('modalRoleName');
    selectAllCheckbox = document.getElementById('selectAll');
    
    // "Tümünü Seç" checkbox'ı için event listener
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function () {
        const isChecked = this.checked;
        const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        
        permissionCheckboxes.forEach(function (checkbox) {
          checkbox.checked = isChecked;
        });
      });
    }
    
    // FormValidation instance oluştur - Vuexy Template standardı
    const addRoleFormValidation = FormValidation.formValidation(addRoleForm, {
      fields: {
        // Rol adı doğrulama kuralları
        modalRoleName: {
          validators: {
            notEmpty: {
              message: typeof __('enter_role_name') !== 'undefined' ? __('enter_role_name') : 'Lütfen bir rol adı girin'
            },
            stringLength: {
              min: 2,
              max: 255,
              message: typeof __('role_name_length') !== 'undefined' ? __('role_name_length') : 'Rol adı 2-255 karakter arasında olmalıdır'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    });
    
    // Rol ekleme/güncelleme işlevleri
    const addOrUpdateRole = function (isEdit = false) {
      if (!roleName || !roleId) {
        console.error('Form fields not found', { roleName, roleId });
        return;
      }
      
      console.log('Starting role update with isEdit:', isEdit);
      console.log('Role data:', { id: roleId.value, name: roleName.value });
      
      // Form verilerini al
      const formData = new FormData();
      formData.append('name', roleName.value);
      
      // Seçili izinleri ekle
      const selectedPermissions = [];
      const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
      permissionCheckboxes.forEach(function (checkbox) {
        selectedPermissions.push(checkbox.value);
        formData.append('permissions[]', checkbox.value);
      });
      
      console.log('Selected permissions:', selectedPermissions);
      
      // CSRF token'ı ekle
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
      } else {
        console.error('CSRF token not found');
        return;
      }
      
      // İstek URL'i ve metodu
      let url, method;
      
      if (isEdit && roleId.value) {
        // Güncelleme için PUT metodu ve ID'li URL
        url = `${window.baseUrl || '/'}admin/role-permissions/${roleId.value}`;
        method = 'PUT';
        formData.append('_method', 'PUT'); // Laravel form method spoofing
      } else {
        // Yeni rol için POST metodu
        url = `${window.baseUrl || '/'}admin/role-permissions/store`;
        method = 'POST';
      }
      
      console.log('Request URL:', url);
      console.log('Request Method:', method);
      
      // AJAX isteği gönder
      fetch(url, {
        method: method,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Response data:', data);
        if (data.success) {
          // Başarılı işlem
          toastr.options = toastOptions;
          toastr.success(data.message || (isEdit ? 'Rol başarıyla güncellendi.' : 'Rol başarıyla oluşturuldu.'));
          
          // Modal'ı kapat ve sayfayı yenile
          const addRoleModalInstance = bootstrap.Modal.getInstance(addRoleModal);
          if (addRoleModalInstance) {
            addRoleModalInstance.hide();
          }
          
          // Sayfayı yenile
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          // Hata durumu
          toastr.options = toastOptions;
          toastr.error(data.message || 'Form doğrulama hatası.');
          
          // Hata mesajlarını göster
          if (data.errors) {
            for (const key in data.errors) {
              if (data.errors.hasOwnProperty(key)) {
                toastr.error(data.errors[key][0]);
              }
            }
          }
        }
      })
      .catch(error => {
        console.error('Fetch Error:', error);
        toastr.options = toastOptions;
        toastr.error('Sunucu hatası: ' + error.message);
      });
    };
    
    // Form gönderildiğinde
    addRoleForm.addEventListener('submit', function (e) {
      e.preventDefault();
      
      // Form doğrulama
      addRoleFormValidation.validate().then(function (status) {
        if (status === 'Valid') {
          console.log('Form is valid, roleId:', roleId ? roleId.value : 'null');
          // Rol ID'si kontrolü ile ekleme veya güncelleme işlemi
          addOrUpdateRole(roleId && roleId.value !== '');
        } else {
          // Form geçerli değil
          toastr.options = toastOptions;
          toastr.error('Form doğrulama hatası.');
        }
      });
    });
  }
  
  // Modal açıldığında
  const setupModalEvents = function() {
    // Tüm rol düzenleme butonlarını bul
    const editButtons = document.querySelectorAll('.role-edit-modal');
    
    if (editButtons.length > 0 && addRoleModal) {
      // Rol düzenleme modalı açıldığında
      addRoleModal.addEventListener('show.bs.modal', function (event) {
        // Modal'ı tetikleyen elementi bul
        const button = event.relatedTarget;
        
        // Eğer button boşsa (null veya undefined) işlemi durdur
        if (!button) return;
        
        // Form öğelerini yeniden seç (DOM yeniden yüklenmiş olabilir)
        roleId = document.getElementById('roleId');
        roleName = document.getElementById('modalRoleName');
        selectAllCheckbox = document.getElementById('selectAll');
        
        if (!roleId || !roleName) return;
        
        // Tüm izin checkbox'larını temizle
        const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        permissionCheckboxes.forEach(checkbox => {
          checkbox.checked = false;
        });
        
        // Tümünü seç checkbox'ını sıfırla
        if (selectAllCheckbox) {
          selectAllCheckbox.checked = false;
        }
        
        // Başlığı ayarla
        const modalTitle = addRoleModal.querySelector('.role-title');
        if (!modalTitle) return;
        
        if (button.classList.contains('add-new-role')) {
          // Yeni rol ekleme modu
          modalTitle.textContent = typeof __('add_new_role') !== 'undefined' ? __('add_new_role') : 'Yeni Rol Ekle';
          if (roleId) roleId.value = '';
          if (roleName) roleName.value = '';
          
          // Form reset
          if (addRoleForm) {
            addRoleForm.reset();
          }
        } else if (button.classList.contains('role-edit-modal')) {
          // Rol düzenleme modu
          modalTitle.textContent = typeof __('edit_role') !== 'undefined' ? __('edit_role') : 'Rolü Düzenle';
          
          // Verileri butondan al
          const id = button.getAttribute('data-id');
          const name = button.getAttribute('data-name');
          const permissions = button.getAttribute('data-permissions');
          
          // Konsola yazdır (debug için)
          console.log('Role Data:', { id, name, permissions });
          
          // Form değerlerini doldur
          if (roleId) roleId.value = id || '';
          if (roleName) roleName.value = name || '';
          
          // İzinleri işaretle (eğer permissions varsa)
          if (permissions) {
            try {
              const permissionList = JSON.parse(permissions);
              
              permissionList.forEach(permission => {
                const checkbox = document.getElementById(permission);
                if (checkbox) {
                  checkbox.checked = true;
                }
              });
              
              // Tüm izinler seçili mi kontrolü
              if (selectAllCheckbox && permissionCheckboxes.length > 0) {
                const allChecked = permissionList.length === permissionCheckboxes.length;
                selectAllCheckbox.checked = allChecked;
              }
            } catch (error) {
              console.error('Permission parsing error:', error);
            }
          }
        }
      });
    }
  };
  
  // Sayfa yüklendiğinde modal olaylarını başlat
  setupModalEvents();
  
  // Rol silme işlemi
  const setupDeleteButtons = function() {
    const deleteRoleButtons = document.querySelectorAll('.delete-role');
    
    if (deleteRoleButtons.length > 0) {
      deleteRoleButtons.forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();
          
          const roleId = this.getAttribute('data-id');
          const roleName = this.getAttribute('data-name');
          
          if (!roleId || !roleName) return;
          
          // SweetAlert2 ile onay al
          Swal.fire({
            title: typeof __('delete_role_confirmation') !== 'undefined' ? __('delete_role_confirmation') : 'Rolü Silmek İstediğinize Emin Misiniz?',
            text: typeof __('delete_role_confirmation_text') !== 'undefined' ? 
              __('delete_role_confirmation_text').replace(':name', roleName) : 
              `${roleName} rolünü silmek istediğinize emin misiniz? Bu işlem geri alınamaz.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: typeof __('yes_delete') !== 'undefined' ? __('yes_delete') : 'Evet, Sil',
            cancelButtonText: typeof __('cancel') !== 'undefined' ? __('cancel') : 'İptal',
            customClass: {
              confirmButton: 'btn btn-danger me-3',
              cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              // CSRF token al
              const csrfToken = document.querySelector('meta[name="csrf-token"]');
              if (!csrfToken) return;
              
              const token = csrfToken.getAttribute('content');
              
              // Form oluştur
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = `${window.baseUrl || ''}admin/role-permissions/${roleId}`;
              form.style.display = 'none';
              
              // CSRF token input'u
              const tokenInput = document.createElement('input');
              tokenInput.type = 'hidden';
              tokenInput.name = '_token';
              tokenInput.value = token;
              
              // Method spoofing input'u
              const methodInput = document.createElement('input');
              methodInput.type = 'hidden';
              methodInput.name = '_method';
              methodInput.value = 'DELETE';
              
              // Input'ları forma ekle
              form.appendChild(tokenInput);
              form.appendChild(methodInput);
              
              // Formu sayfaya ekle ve gönder
              document.body.appendChild(form);
              form.submit();
            }
          });
        });
      });
    }
  };
  
  // Sayfa yüklendiğinde silme butonlarını ayarla
  setupDeleteButtons();
});
