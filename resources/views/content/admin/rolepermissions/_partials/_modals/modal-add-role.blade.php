<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="role-title mb-2">{{ __('add_role') }}</h3>
          <p class="text-muted">{{ __('permissions') }}</p>
        </div>
        <form id="addRoleForm" class="row g-3" method="POST" action="{{ route('admin.role.permissions.store') }}">
          @csrf
          <input type="hidden" id="roleId" name="id" value="">
          <input type="hidden" name="_method" id="formMethod" value="POST">
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">{{ __('role_name') }}</label>
            <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="{{ __('name') }}" tabindex="-1" />
            <div class="error-message text-danger mt-1" id="roleNameError"></div>
          </div>
          <div class="col-12">
            <h5>{{ __('permissions') }}</h5>
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium">{{ __('admin') }} <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('info') }}"></i></td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="selectAll" onclick="toggleAllPermissions(this)" />
                          <label class="form-check-label" for="selectAll">{{ __('all') }}</label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @foreach($permissionCategories ?? [] as $category)
                  <tr>
                    <td class="text-nowrap fw-medium">
                      <i class="{{ $category['icon'] }}"></i> {{ __($category['name']) }}
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input permission-checkbox" type="checkbox" id="perm-{{ $category['slug'] }}.read" name="permissions[]" value="{{ $category['slug'] }}.read" />
                          <label class="form-check-label" for="perm-{{ $category['slug'] }}.read">{{ __('read') }}</label>
                        </div>
                        <div class="form-check mb-0">
                          <input class="form-check-input permission-checkbox" type="checkbox" id="perm-{{ $category['slug'] }}.full" name="permissions[]" value="{{ $category['slug'] }}.full" />
                          <label class="form-check-label" for="perm-{{ $category['slug'] }}.full">{{ __('full') }}</label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @if(isset($category['subCategories']))
                    @foreach($category['subCategories'] as $subCategory)
                    <tr>
                      <td class="text-nowrap fw-medium ps-5">
                        <i class="ti ti-corner-down-right me-2"></i> {{ __($subCategory['name']) }}
                      </td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <div class="form-check mb-0 me-4 me-lg-12">
                            <input class="form-check-input permission-checkbox" type="checkbox" id="perm-{{ $subCategory['slug'] }}.read" name="permissions[]" value="{{ $subCategory['slug'] }}.read" />
                            <label class="form-check-label" for="perm-{{ $subCategory['slug'] }}.read">{{ __('read') }}</label>
                          </div>
                          <div class="form-check mb-0">
                            <input class="form-check-input permission-checkbox" type="checkbox" id="perm-{{ $subCategory['slug'] }}.full" name="permissions[]" value="{{ $subCategory['slug'] }}.full" />
                            <label class="form-check-label" for="perm-{{ $subCategory['slug'] }}.full">{{ __('full') }}</label>
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
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-3" id="submitRoleBtn">{{ __('submit') }}</button>
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
            <div id="loading-indicator" class="spinner-border text-primary d-none" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
const addRoleMessages = {
  enterName: @json(__('name')),
  createdSuccess: @json(__('success')),
  creationError: @json(__('error')),
  actionError: @json(__('error')),
  roleNameRequired: @json(__('form_validation_required')),
  roleNameMinLength: @json(__('form_validation_min_length')),
  roleNameUnique: @json(__('form_validation_is_unique')),
  permissionNotFound: @json(__('permission_not_found'))
};

function validateRoleName(name) {
  const errorElement = document.getElementById('roleNameError');
  if (!name || name.trim() === '') {
    errorElement.textContent = addRoleMessages.roleNameRequired;
    document.getElementById('modalRoleName').classList.add('is-invalid');
    return false;
  }
  if (name.length < 3) {
    errorElement.textContent = addRoleMessages.roleNameMinLength;
    document.getElementById('modalRoleName').classList.add('is-invalid');
    return false;
  }
  errorElement.textContent = '';
  document.getElementById('modalRoleName').classList.remove('is-invalid');
  return true;
}

function toggleAllPermissions(checkbox) {
  const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
  permissionCheckboxes.forEach(function(permCheckbox) {
    permCheckbox.checked = checkbox.checked;
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('addRoleModal');
  const addRoleForm = document.getElementById('addRoleForm');

  if (modal) {
    modal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const roleIdField = document.getElementById('roleId');
      const nameField = document.getElementById('modalRoleName');
      const formMethodField = document.getElementById('formMethod');
      const formElement = document.getElementById('addRoleForm');

      const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
      permissionCheckboxes.forEach(function(checkbox) {
        checkbox.checked = false;
      });

      const selectAllCheckbox = document.getElementById('selectAll');
      if (selectAllCheckbox) selectAllCheckbox.checked = false;

      document.getElementById('roleNameError').textContent = '';
      document.getElementById('modalRoleName').classList.remove('is-invalid');

      if (button.classList.contains('add-new-role')) {
        document.querySelector('.role-title').textContent = '{{ __("add_role") }}';
        roleIdField.value = '';
        nameField.value = '';
        formMethodField.value = 'POST';
        formElement.action = '{{ route("admin.role.permissions.store") }}';
      }
    });
  }

  if (addRoleForm) {
    addRoleForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const roleName = document.getElementById('modalRoleName').value;
      if (!validateRoleName(roleName)) return;

      const loadingIndicator = document.getElementById('loading-indicator');
      const submitButton = document.getElementById('submitRoleBtn');
      if (loadingIndicator) loadingIndicator.classList.remove('d-none');
      if (submitButton) submitButton.disabled = true;

      const selectedPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(cb => cb.value);
      const formData = new FormData(addRoleForm);
      formData.delete('permissions[]');
      selectedPermissions.forEach(p => formData.append('permissions[]', p));

      fetch(addRoleForm.action, {
        method: formData.get('_method') || 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (loadingIndicator) loadingIndicator.classList.add('d-none');
        if (submitButton) submitButton.disabled = false;

        if (data.success) {
          const bsModal = bootstrap.Modal.getInstance(modal);
          if (bsModal) bsModal.hide();

          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showWithReload('success', data.message || addRoleMessages.createdSuccess);
          } else {
            toastr.options = {
              positionClass: 'toast-bottom-center',
              closeButton: true,
              progressBar: true,
              timeOut: 3000,
              onHidden: function() { window.location.reload(); }
            };
            toastr.success(data.message || addRoleMessages.createdSuccess);
          }
        } else {
          if (data.errors) {
            if (data.errors.name) {
              document.getElementById('roleNameError').textContent = data.errors.name[0];
              document.getElementById('modalRoleName').classList.add('is-invalid');
            }
            if (data.errors.permissions) {
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(data.errors.permissions[0] || addRoleMessages.permissionNotFound);
              } else {
                toastr.error(data.errors.permissions[0] || addRoleMessages.permissionNotFound);
              }
            }
          } else {
            if (typeof AppHelpers !== 'undefined') {
              AppHelpers.Messages.showError(data.message || addRoleMessages.creationError);
            } else {
              toastr.error(data.message || addRoleMessages.creationError);
            }
          }
        }
      })
      .catch(err => {
        if (loadingIndicator) loadingIndicator.classList.add('d-none');
        if (submitButton) submitButton.disabled = false;

        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(addRoleMessages.actionError + ': ' + err.message);
        } else {
          toastr.options.positionClass = 'toast-bottom-center';
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr.error(addRoleMessages.actionError + ': ' + err.message);
        }
      });
    });
  }
});
</script>
