<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-edit-role">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">{{ __('edit') }}</h4>
          <p>{{ __('permissions') }}</p>
        </div>
        <!-- Edit role form -->
        <form id="editRoleForm" class="row g-6">
          <input type="hidden" id="editRoleId" name="id" value="">
          <div class="col-12">
            <label class="form-label" for="editRoleName">{{ __('role_name') }}</label>
            <input type="text" id="editRoleName" name="name" class="form-control" placeholder="{{ __('name') }}" tabindex="-1" />
            <div class="error-message text-danger mt-1" id="editRoleNameError"></div>
          </div>
          <div class="col-12">
            <h5 class="mb-6">{{ __('permissions') }}</h5>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium text-heading">{{ __('admin') }} <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('info') }}"></i></td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="editSelectAll" />
                          <label class="form-check-label" for="editSelectAll">
                            {{ __('all') }}
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- Ana Menü Kategorileri -->
                  @foreach($permissionCategories ?? [] as $category)
                  <tr>
                    <td class="text-nowrap fw-medium text-heading">
                      <i class="{{ $category['icon'] }}"></i> {{ __(''.$category['name'].'') }}
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $category['slug'] }}.read" name="permissions[]" value="{{ $category['slug'] }}.read" />
                          <label class="form-check-label" for="edit_{{ $category['slug'] }}.read">
                            {{ __('read') }}
                          </label>
                        </div>
                        <div class="form-check mb-0">
                          <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $category['slug'] }}.full" name="permissions[]" value="{{ $category['slug'] }}.full" />
                          <label class="form-check-label" for="edit_{{ $category['slug'] }}.full">
                            {{ __('full') }}
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- Alt Kategoriler Varsa -->
                  @if(isset($category['subCategories']))
                    @foreach($category['subCategories'] as $subCategory)
                    <tr class="sub-category">
                      <td class="text-nowrap fw-medium ps-5 text-heading">
                        <i class="ti ti-corner-down-right me-2"></i> {{ __(''.$subCategory['name'].'') }}
                      </td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <div class="form-check mb-0 me-4 me-lg-12">
                            <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $subCategory['slug'] }}.read" name="permissions[]" value="{{ $subCategory['slug'] }}.read" />
                            <label class="form-check-label" for="edit_{{ $subCategory['slug'] }}.read">
                              {{ __('read') }}
                            </label>
                          </div>
                          <div class="form-check mb-0">
                            <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $subCategory['slug'] }}.full" name="permissions[]" value="{{ $subCategory['slug'] }}.full" />
                            <label class="form-check-label" for="edit_{{ $subCategory['slug'] }}.full">
                              {{ __('full') }}
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3" id="updateRoleBtn">{{ __('update') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
            <div id="edit-loading-indicator" class="spinner-border text-primary d-none" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </form>
        <!--/ Edit role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Edit Role Modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
  const editModal = document.getElementById('editRoleModal');
  const editRoleForm = document.getElementById('editRoleForm');
  const editSelectAll = document.getElementById('editSelectAll');

  // Mesaj sabitleri
  const editRoleMessages = {
    roleNameRequired: @json(__('form_validation_required')),
    roleNameMinLength: @json(__('form_validation_min_length')),
    roleNameUnique: @json(__('form_validation_is_unique')),
    updatedSuccess: @json(__('success')),
    updateError: @json(__('error')),
    actionError: @json(__('error'))
  };

  // Rol adı doğrulama fonksiyonu
  function validateEditRoleName(name) {
    const errorElement = document.getElementById('editRoleNameError');

    if (!name || name.trim() === '') {
      errorElement.textContent = editRoleMessages.roleNameRequired;
      document.getElementById('editRoleName').classList.add('is-invalid');
      return false;
    }

    if (name.length < 3) {
      errorElement.textContent = editRoleMessages.roleNameMinLength;
      document.getElementById('editRoleName').classList.add('is-invalid');
      return false;
    }

    errorElement.textContent = '';
    document.getElementById('editRoleName').classList.remove('is-invalid');
    return true;
  }

  // Hata ayıklama için global değişkenler
  window.lastEditRoleData = null;
  window.lastEditResponse = null;

  // "Tümünü Seç" checkbox işlevselliği
  if (editSelectAll) {
    editSelectAll.addEventListener('change', function() {
      const isChecked = this.checked;
      const permissionCheckboxes = document.querySelectorAll('.edit-permission');

      permissionCheckboxes.forEach(function(checkbox) {
        checkbox.checked = isChecked;
      });
    });
  }

  // Düzenleme modalı açıldığında
  if (editModal) {
    editModal.addEventListener('show.bs.modal', function(event) {
      // Hata mesajını temizle
      document.getElementById('editRoleNameError').textContent = '';
      document.getElementById('editRoleName').classList.remove('is-invalid');

      const button = event.relatedTarget;

      // Rol verilerini al
      const roleId = button.getAttribute('data-id');
      const roleName = button.getAttribute('data-name');
      const permissions = button.getAttribute('data-permissions');

      // Form alanlarını doldur
      const editRoleIdField = document.getElementById('editRoleId');
      const editRoleNameField = document.getElementById('editRoleName');

      if (editRoleIdField && editRoleNameField) {
        editRoleIdField.value = roleId;
        editRoleNameField.value = roleName;
      }

      // Tüm izinleri temizle
      const permissionCheckboxes = document.querySelectorAll('.edit-permission');
      permissionCheckboxes.forEach(function(checkbox) {
        checkbox.checked = false;
      });

      // Tümünü seç'i sıfırla
      if (editSelectAll) {
        editSelectAll.checked = false;
      }

      // İzinleri işaretle
      try {
        if (permissions) {
          let permissionList;

          if (typeof permissions === 'string') {
            try {
              permissionList = JSON.parse(permissions);
            } catch (e) {
              // JSON parse hatası
              permissionList = []; // Hata durumunda boş liste
            }
          } else {
            permissionList = permissions; // Zaten array ise
          }

          // Her izni işaretle
          if (Array.isArray(permissionList)) {
            let foundCount = 0;
            permissionList.forEach(function(permissionName) {
              // ID doğrudan kullan: 'edit_' + permissionName
              const permCheckboxId = 'edit_' + permissionName;

              try {
                const permCheckbox = document.getElementById(permCheckboxId);
                if (permCheckbox) {
                  permCheckbox.checked = true;
                  foundCount++;
                }
              } catch (e) {
                // ID lookup hatası
              }
            });

            // Tümünü seç checkbox'ını güncelle
            const allEditPermissions = document.querySelectorAll('.edit-permission');
            if (editSelectAll && allEditPermissions.length > 0 && foundCount === allEditPermissions.length) {
              editSelectAll.checked = true;
            }
          }
        }
      } catch (error) {
        // İzin işleme hatası
      }
    });
  }

  // Form gönderildiğinde
  if (editRoleForm) {
    editRoleForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Form doğrulama
      const roleId = document.getElementById('editRoleId').value;
      const roleName = document.getElementById('editRoleName').value;

      // İsim alanı doğrulama
      if (!validateEditRoleName(roleName)) {
        return;
      }

      // Yükleniyor göstergesini göster
      const loadingIndicator = document.getElementById('edit-loading-indicator');
      const submitButton = document.getElementById('updateRoleBtn');

      if (loadingIndicator) loadingIndicator.classList.remove('d-none');
      if (submitButton) submitButton.disabled = true;

      // Form verilerini al
      const formData = new FormData();

      formData.append('id', roleId);
      formData.append('name', roleName);

      // CSRF token ekle
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (csrfToken) {
        formData.append('_token', csrfToken);
      }

      // PUT metodu için _method ekle
      formData.append('_method', 'PUT');

      // Seçili izinleri ekle
      const selectedPermissions = [];
      const permissionCheckboxes = document.querySelectorAll('.edit-permission:checked');

      permissionCheckboxes.forEach(function(checkbox) {
        selectedPermissions.push(checkbox.value);
      });

      // İzinleri eklemeden önce mevcut olanları temizle
      for (const pair of formData.entries()) {
        if (pair[0] === 'permissions[]') {
          formData.delete('permissions[]');
          break;
        }
      }

      // İzin yoksa boş bir dizi olarak gönder, varsa her izni ayrı ayrı ekle
      if (selectedPermissions.length > 0) {
        selectedPermissions.forEach(function(permission) {
          formData.append('permissions[]', permission);
        });
      } else {
        formData.append('permissions', '[]');
      }

      // Json formatında ikinci bir yöntem olarak da gönderelim
      const jsonData = {
        id: roleId,
        name: roleName,
        permissions: selectedPermissions,
        _method: 'PUT',
        _token: csrfToken
      };

      // Global değişkene kaydet
      window.lastEditRoleData = {
        formData: Object.fromEntries(formData),
        jsonData: jsonData,
        selectedPermissions: selectedPermissions
      };

      // AJAX ile formu gönder - FormData ile
      fetch(`${window.baseUrl || ''}admin/role-permissions/${roleId}`, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      })
      .then(response => {
        return response.json();
      })
      .then(data => {
        // Global değişkene kaydet
        window.lastEditResponse = data;

        // Yükleniyor göstergesini gizle
        if (loadingIndicator) loadingIndicator.classList.add('d-none');
        if (submitButton) submitButton.disabled = false;

        if (data.success) {
          // Başarılı - Modal'ı kapat
          const modalInstance = bootstrap.Modal.getInstance(editModal);
          if (modalInstance) modalInstance.hide();

          // Toast'u göster ve kapandığında sayfayı yenile
          toastr.options = {
            positionClass: 'toast-bottom-center',
            closeButton: true,
            progressBar: true,
            timeOut: 3000,
            onHidden: function() {
              window.location.reload();
            }
          };
          toastr.success(data.message || editRoleMessages.updatedSuccess);
        } else {
          // Validasyon hatalarını göster
          if (data.errors) {
            // İsim hatalarını input altında göster
            if (data.errors.name) {
              document.getElementById('editRoleNameError').textContent = data.errors.name[0];
              document.getElementById('editRoleName').classList.add('is-invalid');
            }

            // Diğer hatalar varsa konsola yaz (debug amaçlı)
          } else {
            toastr.options = {
              positionClass: 'toast-bottom-center',
              closeButton: true,
              progressBar: true
            };
            toastr.error(data.message || editRoleMessages.updateError);
          }
        }
      })
      .catch(error => {
        // Yükleniyor göstergesini gizle
        if (loadingIndicator) loadingIndicator.classList.add('d-none');
        if (submitButton) submitButton.disabled = false;

        toastr.options = {
          positionClass: 'toast-bottom-center',
          closeButton: true,
          progressBar: true
        };
        toastr.error(editRoleMessages.actionError);

        // FormData başarısız olursa JSON göndermeyi dene
        fetch(`${window.baseUrl || ''}admin/role-permissions/${roleId}`, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
          window.lastEditResponse = data;

          if (data.success) {
            toastr.options = {
              positionClass: 'toast-bottom-center',
              closeButton: true,
              progressBar: true,
              timeOut: 3000,
              onHidden: function() {
                window.location.reload();
              }
            };
            toastr.success(data.message || editRoleMessages.updatedSuccess);
          } else {
            toastr.options = { positionClass: 'toast-bottom-center', closeButton: true, progressBar: true };
            toastr.error(data.message || editRoleMessages.updateError);
          }
        })
        .catch(jsonError => {
          toastr.options = { positionClass: 'toast-bottom-center', closeButton: true, progressBar: true };
          toastr.error(editRoleMessages.actionError);
        });
      });
    });
  }
});
</script>
