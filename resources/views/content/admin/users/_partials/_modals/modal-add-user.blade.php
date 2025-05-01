<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-add-user">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="mb-2">{{ __('add_user') }}</h4>
          <p>{{ __('add_new_user_instruction') }}</p>
        </div>
        <form id="addUserForm" class="row g-6" onsubmit="return false">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <!-- User Name -->
          <div class="col-12 col-md-12">
            <label class="form-label" for="add-user-fullname">{{ __('full_name') }} <span class="text-danger">*</span></label>
            <input
              type="text"
              id="add-user-fullname"
              name="name"
              class="form-control"
              placeholder="{{ __('john_doe') }}"
              required
            />
          </div>

          <!-- Username -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-username">{{ __('username') }} <span class="text-danger">*</span></label>
            <input
              type="text"
              id="add-user-username"
              name="username"
              class="form-control"
              placeholder="{{ __('johndoe') }}"
              required
            />
          </div>

          <!-- Email -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-email">{{ __('email') }} <span class="text-danger">*</span></label>
            <input
              type="email"
              id="add-user-email"
              name="email"
              class="form-control"
              placeholder="{{ __('example@domain.com') }}"
              required
            />
          </div>

          <!-- Role -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-role">{{ __('role') }} <span class="text-danger">*</span></label>
            <select id="add-user-role" name="role_id" class="form-select" required>
              <option value="">{{ __('select_role') }}</option>
              <!-- Roles will be populated dynamically -->
            </select>
          </div>

          <div class="col-12 col-md-6">
            <!-- Boş div, burayı boş bırakıyoruz -->
          </div>

          <!-- Password -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-password">{{ __('password') }} <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
              <input
                type="password"
                id="add-user-password"
                name="password"
                class="form-control"
                placeholder="{{ __('············') }}"
                minlength="4"
                required
              />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="add-user-confirm-password">{{ __('confirm_password') }} <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
              <input
                type="password"
                id="add-user-confirm-password"
                name="password_confirmation"
                class="form-control"
                placeholder="{{ __('············') }}"
                minlength="4"
                required
              />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>

          <!-- Reward System -->
          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="add-user-reward" name="reward_system_active" />
              <label class="form-check-label" for="add-user-reward">{{ __('enable_reward_system') }}</label>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('save') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add User Modal -->
