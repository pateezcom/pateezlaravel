<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Kullanıcı Düzenle') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning d-none" id="edit-form-errors">
          <div class="alert-body fw-normal"></div>
        </div>
        <form id="editUserForm" class="row">
          @csrf
          <input type="hidden" id="edit-user-id" name="userId">
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" id="edit-user-fullname" name="editUserFullname" class="form-control" placeholder="John Doe" />
              <label for="edit-user-fullname">{{ __('Ad Soyad') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" id="edit-user-username" name="editUserUsername" class="form-control" placeholder="johndoe" />
              <label for="edit-user-username">{{ __('Kullanıcı Adı') }}</label>
            </div>
            <div id="username-availability"></div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="email" id="edit-user-email" name="editUserEmail" class="form-control" placeholder="john.doe@example.com" />
              <label for="edit-user-email">{{ __('E-posta Adresi') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <select id="edit-user-role" name="editUserRole" class="form-select">
                <option value="">{{ __('Rol Seçin') }}</option>
                <option value="Admin">{{ __('Yönetici') }}</option>
                <option value="Moderator">{{ __('Moderatör') }}</option>
                <option value="Author">{{ __('Yazar') }}</option>
                <option value="Member">{{ __('Üye') }}</option>
              </select>
              <label for="edit-user-role">{{ __('Kullanıcı Rolü') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <select id="edit-user-status" name="editUserStatus" class="form-select">
                <option value="2">{{ __('Aktif') }}</option>
                <option value="1">{{ __('Beklemede') }}</option>
                <option value="3">{{ __('Pasif') }}</option>
              </select>
              <label for="edit-user-status">{{ __('Durum') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="password" id="edit-user-password" name="editUserPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
              <label for="edit-user-password">{{ __('Şifre (Boş bırakılırsa değişmez)') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="password" id="edit-user-confirm-password" name="editUserConfirmPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
              <label for="edit-user-confirm-password">{{ __('Şifre Onayı') }}</label>
            </div>
          </div>
          <div class="col-12 mb-4">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="edit-user-reward" name="editUserReward">
              <label class="form-check-label" for="edit-user-reward">{{ __('Ödül Sistemi Aktif') }}</label>
            </div>
          </div>
          <div class="col-12 mb-4 d-flex justify-content-between">
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('İptal') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Kaydet') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit User Modal -->

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-add-user">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Yeni Kullanıcı Ekle') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning d-none" id="add-form-errors">
          <div class="alert-body fw-normal"></div>
        </div>
        <form id="addNewUserForm" class="row">
          @csrf
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" id="add-user-fullname" name="userFullname" class="form-control" placeholder="John Doe" />
              <label for="add-user-fullname">{{ __('Ad Soyad') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" id="add-user-username" name="userUsername" class="form-control" placeholder="johndoe" />
              <label for="add-user-username">{{ __('Kullanıcı Adı') }}</label>
            </div>
            <div id="username-availability-add"></div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="email" id="add-user-email" name="userEmail" class="form-control" placeholder="john.doe@example.com" />
              <label for="add-user-email">{{ __('E-posta Adresi') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <select id="add-user-role" name="userRole" class="form-select">
                <option value="">{{ __('Rol Seçin') }}</option>
                <option value="Admin">{{ __('Yönetici') }}</option>
                <option value="Moderator">{{ __('Moderatör') }}</option>
                <option value="Author">{{ __('Yazar') }}</option>
                <option value="Member">{{ __('Üye') }}</option>
              </select>
              <label for="add-user-role">{{ __('Kullanıcı Rolü') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="password" id="add-user-password" name="userPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
              <label for="add-user-password">{{ __('Şifre') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="password" id="add-user-confirm-password" name="confirmPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
              <label for="add-user-confirm-password">{{ __('Şifre Onayı') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <label class="form-label d-block">{{ __('Durum') }}</label>
            <div class="form-check form-check-inline mt-2">
              <input class="form-check-input" type="radio" name="userStatus" id="user-active" value="2" checked />
              <label class="form-check-label" for="user-active">{{ __('Aktif') }}</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="userStatus" id="user-inactive" value="3" />
              <label class="form-check-label" for="user-inactive">{{ __('Pasif') }}</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="userStatus" id="user-pending" value="1" />
              <label class="form-check-label" for="user-pending">{{ __('Beklemede') }}</label>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="user-reward" name="userReward" value="1" />
              <label class="form-check-label" for="user-reward">{{ __('Ödül Sistemi Aktif') }}</label>
            </div>
          </div>
          <div class="col-12 mb-4 d-flex justify-content-between">
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('İptal') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Kaydet') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add User Modal -->

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
