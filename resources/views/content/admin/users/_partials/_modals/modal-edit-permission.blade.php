<!-- Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Kullanıcı İzinleri') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning d-none" id="permission-form-errors">
          <div class="alert-body fw-normal"></div>
        </div>
        <form id="editPermissionForm">
          @csrf
          <input type="hidden" id="permission-user-id" name="permissionUserId">
          
          <h6 class="fw-bold">{{ __('Sistem İzinleri') }}</h6>
          <div class="mb-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="selectAll" />
              <label class="form-check-label fw-bold" for="selectAll">
                {{ __('Tümünü Seç') }}
              </label>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12 mb-4">
              <h6>{{ __('Kullanıcı Yönetimi') }}</h6>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="userManagementRead" name="permissions[]" value="read-users" />
                  <label class="form-check-label" for="userManagementRead">
                    {{ __('Görüntüleme') }}
                  </label>
                </div>
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="userManagementWrite" name="permissions[]" value="edit-users" />
                  <label class="form-check-label" for="userManagementWrite">
                    {{ __('Düzenleme') }}
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="userManagementDelete" name="permissions[]" value="delete-users" />
                  <label class="form-check-label" for="userManagementDelete">
                    {{ __('Silme') }}
                  </label>
                </div>
              </div>
            </div>
            
            <div class="col-12 mb-4">
              <h6>{{ __('İçerik Yönetimi') }}</h6>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="contentManagementRead" name="permissions[]" value="read-content" />
                  <label class="form-check-label" for="contentManagementRead">
                    {{ __('Görüntüleme') }}
                  </label>
                </div>
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="contentManagementWrite" name="permissions[]" value="edit-content" />
                  <label class="form-check-label" for="contentManagementWrite">
                    {{ __('Düzenleme') }}
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="contentManagementDelete" name="permissions[]" value="delete-content" />
                  <label class="form-check-label" for="contentManagementDelete">
                    {{ __('Silme') }}
                  </label>
                </div>
              </div>
            </div>
            
            <div class="col-12 mb-4">
              <h6>{{ __('Dosya Yönetimi') }}</h6>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="fileManagementRead" name="permissions[]" value="read-files" />
                  <label class="form-check-label" for="fileManagementRead">
                    {{ __('Görüntüleme') }}
                  </label>
                </div>
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="fileManagementWrite" name="permissions[]" value="edit-files" />
                  <label class="form-check-label" for="fileManagementWrite">
                    {{ __('Düzenleme') }}
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="fileManagementDelete" name="permissions[]" value="delete-files" />
                  <label class="form-check-label" for="fileManagementDelete">
                    {{ __('Silme') }}
                  </label>
                </div>
              </div>
            </div>
            
            <div class="col-12 mb-4">
              <h6>{{ __('Ayarlar') }}</h6>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3 mb-2">
                  <input class="form-check-input" type="checkbox" id="settingsRead" name="permissions[]" value="read-settings" />
                  <label class="form-check-label" for="settingsRead">
                    {{ __('Görüntüleme') }}
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="settingsWrite" name="permissions[]" value="edit-settings" />
                  <label class="form-check-label" for="settingsWrite">
                    {{ __('Düzenleme') }}
                  </label>
                </div>
              </div>
            </div>
            
            <div class="col-12 d-flex justify-content-between">
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                {{ __('İptal') }}
              </button>
              <button type="submit" class="btn btn-primary">{{ __('Kaydet') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Permission Modal -->
