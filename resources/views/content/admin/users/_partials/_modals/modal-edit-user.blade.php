<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="mb-2">{{ __('edit_user_information') }}</h4>
          <p>{{ __('update_user_details_instruction') }}</p>
        </div>
        <form id="editUserForm" class="row g-6" onsubmit="return false">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="edit-user-id" name="id">
          <input type="hidden" name="_method" value="PUT">

          <!-- User Name -->
          <div class="col-12 col-md-12">
            <label class="form-label" for="edit-user-fullname">{{ __('full_name') }} <span class="text-danger">*</span></label>
            <input
              type="text"
              id="edit-user-fullname"
              name="name"
              class="form-control"
              placeholder="{{ __('john_doe') }}"
              required
            />
          </div>

          <!-- Username -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-username">{{ __('username') }} <span class="text-danger">*</span></label>
            <input
              type="text"
              id="edit-user-username"
              name="username"
              class="form-control"
              placeholder="{{ __('johndoe') }}"
              required
            />
          </div>

          <!-- Email -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-email">{{ __('email') }} <span class="text-danger">*</span></label>
            <input
              type="email"
              id="edit-user-email"
              name="email"
              class="form-control"
              placeholder="{{ __('example@domain.com') }}"
              required
            />
          </div>

          <!-- Role -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-role">{{ __('role') }} <span class="text-danger">*</span></label>
            <select id="edit-user-role" name="role_id" class="form-select" required>
              <option value="">{{ __('select_role') }}</option>
              <!-- Roles will be populated dynamically -->
            </select>
          </div>

          <!-- Status -->
          <div class="col-12 col-md-6">
            <label class="form-label">{{ __('status') }}</label>
            <div class="d-flex flex-wrap">
              <div class="form-check me-3 me-lg-5 mt-2">
                <input class="form-check-input" type="radio" name="status" id="edit-user-status-pending" value="0">
                <label class="form-check-label" for="edit-user-status-pending">
                  {{ __('pending') }}
                </label>
              </div>
              <div class="form-check me-3 me-lg-5 mt-2">
                <input class="form-check-input" type="radio" name="status" id="edit-user-status-inactive" value="1">
                <label class="form-check-label" for="edit-user-status-inactive">
                  {{ __('inactive') }}
                </label>
              </div>
              <div class="form-check me-3 me-lg-5 mt-2">
                <input class="form-check-input" type="radio" name="status" id="edit-user-status-active" value="2">
                <label class="form-check-label" for="edit-user-status-active">
                  {{ __('active') }}
                </label>
              </div>
            </div>
          </div>

          <!-- Password heading (optional) -->
          <div class="col-12">
            <h6 class="mt-2">{{ __('change_password') }} <small class="text-muted">({{ __('optional') }})</small></h6>
            <hr class="mt-0" />
          </div>

          <!-- Password -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-password">{{ __('new_password') }}</label>
            <div class="input-group input-group-merge">
              <input
                type="password"
                id="edit-user-password"
                name="password"
                class="form-control"
                placeholder="{{ __('············') }}"
                minlength="4"
              />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="col-12 col-md-6">
            <label class="form-label" for="edit-user-confirm-password">{{ __('confirm_password') }}</label>
            <div class="input-group input-group-merge">
              <input
                type="password"
                id="edit-user-confirm-password"
                name="password_confirmation"
                class="form-control"
                placeholder="{{ __('············') }}"
                minlength="4"
              />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>

          <!-- Reward System -->
          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="edit-user-reward" name="reward_system_active" />
              <label class="form-check-label" for="edit-user-reward">{{ __('enable_reward_system') }}</label>
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
<!--/ Edit User Modal -->
