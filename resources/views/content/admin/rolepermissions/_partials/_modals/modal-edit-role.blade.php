<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-edit-role">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">{{ __('edit_role') }}</h4>
          <p>{{ __('update_role_permissions') }}</p>
        </div>
        <!-- Edit role form -->
        <form id="editRoleForm" class="row g-6" onsubmit="return false">
          <input type="hidden" id="editRoleId" name="editRoleId" value="">
          <div class="col-12">
            <label class="form-label" for="editRoleName">{{ __('role_name') }}</label>
            <input type="text" id="editRoleName" name="editRoleName" class="form-control" placeholder="{{ __('enter_role_name') }}" tabindex="-1" />
          </div>
          <div class="col-12">
            <h5 class="mb-6">{{ __('role_permissions') }}</h5>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium text-heading">{{ __('administrator_access') }} <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin_access_info') }}"></i></td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="editSelectAll" />
                          <label class="form-check-label" for="editSelectAll">
                            {{ __('select_all') }}
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
                            {{ __('read_permission') }}
                          </label>
                        </div>
                        <div class="form-check mb-0">
                          <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $category['slug'] }}.full" name="permissions[]" value="{{ $category['slug'] }}.full" />
                          <label class="form-check-label" for="edit_{{ $category['slug'] }}.full">
                            {{ __('full_permission') }}
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
                              {{ __('read_permission') }}
                            </label>
                          </div>
                          <div class="form-check mb-0">
                            <input class="form-check-input edit-permission" type="checkbox" id="edit_{{ $subCategory['slug'] }}.full" name="permissions[]" value="{{ $subCategory['slug'] }}.full" />
                            <label class="form-check-label" for="edit_{{ $subCategory['slug'] }}.full">
                              {{ __('full_permission') }}
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
            <button type="submit" class="btn btn-primary me-3">{{ __('update') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
          </div>
        </form>
        <!--/ Edit role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Edit Role Modal -->
