<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-add-user">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="address-title mb-2">{{ __('Yeni Kullanıcı Ekle') }}</h4>
          <p class="address-subtitle">{{ __('Sisteme yeni bir kullanıcı ekleyin') }}</p>
        </div>
        
        <div class="alert alert-warning d-none" id="add-form-errors">
          <div class="alert-body fw-normal"></div>
        </div>
        
        <form id="addNewUserForm" class="row g-6" onsubmit="return false">
          @csrf
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-fullname">{{ __('Ad Soyad') }}</label>
            <input type="text" id="add-user-fullname" name="userFullname" class="form-control" placeholder="John Doe" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-username">{{ __('Kullanıcı Adı') }}</label>
            <input type="text" id="add-user-username" name="userUsername" class="form-control" placeholder="johndoe" />
            <div id="username-availability-add"></div>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-email">{{ __('E-posta Adresi') }}</label>
            <input type="email" id="add-user-email" name="userEmail" class="form-control" placeholder="john.doe@example.com" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-role">{{ __('Kullanıcı Rolü') }}</label>
            <select id="add-user-role" name="userRole" class="form-select" data-allow-clear="true">
              <option value="">{{ __('Rol Seçin') }}</option>
              <option value="Admin">{{ __('Yönetici') }}</option>
              <option value="Moderator">{{ __('Moderatör') }}</option>
              <option value="Author">{{ __('Yazar') }}</option>
              <option value="Member">{{ __('Üye') }}</option>
            </select>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-password">{{ __('Şifre') }}</label>
            <input type="password" id="add-user-password" name="userPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-confirm-password">{{ __('Şifre Onayı') }}</label>
            <input type="password" id="add-user-confirm-password" name="confirmPassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label d-block">{{ __('Durum') }}</label>
            <div class="mt-2">
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="userStatus" id="user-active" value="2" checked />
                <label class="form-check-label" for="user-active">{{ __('Aktif') }}</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="userStatus" id="user-inactive" value="1" />
                <label class="form-check-label" for="user-inactive">{{ __('Pasif') }}</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="userStatus" id="user-pending" value="0" />
                <label class="form-check-label" for="user-pending">{{ __('Beklemede') }}</label>
              </div>
            </div>
          </div>
          
          <div class="col-12 col-md-6">
            <label class="form-label d-block">{{ __('Ödül Seçenekleri') }}</label>
            <div class="mt-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="user-reward" name="userReward" value="1" />
                <label class="form-check-label" for="user-reward">{{ __('Ödül Sistemi Aktif') }}</label>
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
<!--/ Add User Modal -->
