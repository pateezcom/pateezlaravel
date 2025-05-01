@extends('layouts/layoutMaster')

@section('title', __('Users'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/toastr/toastr.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/toastr/toastr.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
@vite([
  'resources/js/admin/users/user-list.js',
  'resources/js/admin/users/user-add-form-validation.js',
  'resources/js/admin/users/user-edit-form-validation.js',
])
@endsection

@section('content')

<div class="row g-6 mb-6">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">{{ __('total_users') }}</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">{{ number_format($stats['total']['count']) }}</h4>
              <p class="{{ $stats['total']['increase'] ? 'text-success' : 'text-danger' }} mb-0">
                ({{ $stats['total']['increase'] ? '+' : '-' }}{{ $stats['total']['change'] }}%)
              </p>
            </div>
            <small class="mb-0">{{ __('overall_registered_users') }}</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="ti ti-users ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">{{ __('reward_users') }}</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">{{ number_format($stats['reward']['count']) }}</h4>
              <p class="{{ $stats['reward']['increase'] ? 'text-success' : 'text-danger' }} mb-0">
                ({{ $stats['reward']['increase'] ? '+' : '-' }}{{ $stats['reward']['change'] }}%)
              </p>
            </div>
            <small class="mb-0">{{ __('users_with_active_rewards') }}</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="ti ti-trophy ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">{{ __('active_users') }}</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">{{ number_format($stats['active']['count']) }}</h4>
              <p class="{{ $stats['active']['increase'] ? 'text-success' : 'text-danger' }} mb-0">
                ({{ $stats['active']['increase'] ? '+' : '-' }}{{ $stats['active']['change'] }}%)
              </p>
            </div>
            <small class="mb-0">{{ __('currently_active_accounts') }}</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="ti ti-user-check ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">{{ __('pending_users') }}</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">{{ number_format($stats['pending']['count']) }}</h4>
              <p class="{{ $stats['pending']['increase'] ? 'text-success' : 'text-danger' }} mb-0">
                ({{ $stats['pending']['increase'] ? '+' : '-' }}{{ $stats['pending']['change'] }}%)
              </p>
            </div>
            <small class="mb-0">{{ __('awaiting_account_verification') }}</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="ti ti-user-search ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-0">{{ __('filter') }}</h5>
    <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
      <div class="col-md-4 user_role"></div>
      <div class="col-md-4 user_reward"></div>
      <div class="col-md-4 user_status"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-users table">
      <thead class="border-top">
        <tr>
          <th></th>
          <th></th>
          <th>{{ __('user') }}</th>
          <th>{{ __('role') }}</th>
          <th>{{ __('reward_system') }}</th>
          <th>{{ __('status') }}</th>
          <th>{{ __('date') }}</th>
          <th>{{ __('options') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('content.admin.users._partials._modals.modal-add-user')
@include('content.admin.users._partials._modals.modal-edit-user')

@endsection
