<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="address-title mb-2">{{ __('Kullanıcı Düzenle') }}</h4>
          <p class="address-subtitle">{{ __('Kullanıcı bilgilerini güncelleyin') }}</p>
        </div>
        
        <div class="alert alert-warning d-none" id="edit-form-errors">
          <div class="alert-body fw-normal"></div>
        </div>
        
        <form id="editUserForm" class="row g-6" onsubmit="return false">
          @csrf
          <input type="hidden" id="edit-user-id" name="userId">
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-fullname">{{ __('Ad Soyad') }}</label>
            <input type="text" id="edit-user-fullname" name="editUserFullname" class="form-control" placeholder="John Doe" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-username">{{ __('Kullanıcı Adı') }}</label>
            <input type="text" id="edit-user-username" name="editUserUsername" class="form-control" placeholder="johndoe" />
            <div id="username-availability"></div>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-email">{{ __('E-posta Adresi') }}</label>
            <input type="email" id="edit-user-email" name="editUserEmail" class="form-control" placeholder="john.doe@example.com" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-role">{{ __('Kullanıcı Rolü') }}</label>
            <select id="edit-user-role" name="editUserRole" class="form-select" data-allow-clear="true">
              <option value="">{{ __('Rol Seçin') }}</option>
              <option value="Admin">{{ __('Yönetici') }}</option>
              <option value="Moderator">{{ __('Moderatör') }}</option>
              <option value="Author">{{ __('Yazar') }}</option>
              <option value="Member">{{ __('Üye') }}</option>
            </select>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-password">{{ __('Şifre (Boş bırakılırsa değişmez)') }}</label>
            <input type="password" id="edit-user-password" name="editUserPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-confirm-password">{{ __('Şifre Onayı') }}</label>
            <input type="password" id="edit-user-confirm-password" name="editUserConfirmPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label d-block">{{ __('Durum') }}</label>
            <div class="mt-2">
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="editUserStatus" id="edit-user-active" value="2" />
                <label class="form-check-label" for="edit-user-active">{{ __('Aktif') }}</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="editUserStatus" id="edit-user-inactive" value="1" />
                <label class="form-check-label" for="edit-user-inactive">{{ __('Pasif') }}</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="editUserStatus" id="edit-user-pending" value="0" />
                <label class="form-check-label" for="edit-user-pending">{{ __('Beklemede') }}</label>
              </div>
            </div>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label d-block">{{ __('Ödül Seçenekleri') }}</label>
            <div class="mt-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="edit-user-reward" name="editUserReward" />
                <label class="form-check-label" for="edit-user-reward">{{ __('Ödül Sistemi Aktif') }}</label>
              </div>
            </div>
          </div>
          
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3">{{ __('Kaydet') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('İptal') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit User Modal -->
