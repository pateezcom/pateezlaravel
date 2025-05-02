<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="role-title mb-2">{{ __('add_new_role') }}</h3>
          <p class="text-muted">{{ __('set_role_permissions') }}</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-3" onsubmit="return false">
          <input type="hidden" id="roleId" name="roleId" value="">
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">{{ __('role_name') }}</label>
            <input type="text" id="modalRoleName" name="modalRoleName" class="form-control" placeholder="{{ __('enter_role_name') }}" tabindex="-1" />
          </div>
          <div class="col-12">
            <h5>{{ __('role_permissions') }}</h5>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium">{{ __('administrator_access') }} <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin_access_info') }}"></i></td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="selectAll" />
                          <label class="form-check-label" for="selectAll">
                            {{ __('select_all') }}
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Ana Menü Kategorileri -->
                  @foreach($permissionCategories ?? [] as $category)
                  <tr>
                    <td class="text-nowrap fw-medium">
                      <i class="{{ $category['icon'] }}"></i> {{ __(''.$category['name'].'') }}
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input" type="checkbox" id="{{ $category['slug'] }}.read" name="permissions[]" value="{{ $category['slug'] }}.read" />
                          <label class="form-check-label" for="{{ $category['slug'] }}.read">
                            {{ __('read_permission') }}
                          </label>
                        </div>
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="{{ $category['slug'] }}.full" name="permissions[]" value="{{ $category['slug'] }}.full" />
                          <label class="form-check-label" for="{{ $category['slug'] }}.full">
                            {{ __('full_permission') }}
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Alt Kategoriler Varsa -->
                  @if(isset($category['subCategories']))
                    @foreach($category['subCategories'] as $subCategory)
                    <tr>
                      <td class="text-nowrap fw-medium ps-5">
                        <i class="ti ti-corner-down-right me-2"></i> {{ __(''.$subCategory['name'].'') }}
                      </td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <div class="form-check mb-0 me-4 me-lg-12">
                            <input class="form-check-input" type="checkbox" id="{{ $subCategory['slug'] }}.read" name="permissions[]" value="{{ $subCategory['slug'] }}.read" />
                            <label class="form-check-label" for="{{ $subCategory['slug'] }}.read">
                              {{ __('read_permission') }}
                            </label>
                          </div>
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="{{ $subCategory['slug'] }}.full" name="permissions[]" value="{{ $subCategory['slug'] }}.full" />
                            <label class="form-check-label" for="{{ $subCategory['slug'] }}.full">
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
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-3">{{ __('submit') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
