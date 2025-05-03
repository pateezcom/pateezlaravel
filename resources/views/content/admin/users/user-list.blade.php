@extends('layouts/layoutMaster')

@section('title', __('user_list'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/datatables-select-bs5/select.bootstrap5.scss',
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
<!-- Content -->
<div class="container-fluid mt-3">
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">{{ __('users') }} /</span> {{ __('list') }}
  </h4>

  <!-- DataTable with Buttons -->
  <div class="card">
    <div class="card-header border-bottom">
      <div class="row align-items-center">
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
  <!--/ DataTable with Buttons -->

  <!-- Modals -->
  @include('content.admin.users._partials._modals.modal-add-user')
  @include('content.admin.users._partials._modals.modal-edit-user')
  <!--/ Modals -->
</div>
<!-- / Content -->
@endsection
