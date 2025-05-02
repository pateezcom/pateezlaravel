@extends('layouts/layoutMaster')

@php
$activeMenu = 'admin.users';
@endphp

@section('title', $user->name . ' - ' . __('update_profile'))

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/toastr/toastr.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/toastr/toastr.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/js/admin/users/user-profile-security-form-validation.js'
])
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <!-- Başarı ve Hata Mesajları -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
      <div class="alert-body">{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('close') }}"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
      <div class="alert-body">{{ session('error') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('close') }}"></button>
    </div>
    @endif

    <div class="nav-align-top mb-4">
      <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.users.profile', $user->id) }}">
            <i class="ti-sm ti ti-users me-1"></i> {{ __('account_details') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="javascript:void(0);">
            <i class="ti-sm ti ti-lock me-1"></i> {{ __('security') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Şifre Değiştirme -->
    <div class="card mb-4">
      <h5 class="card-header">{{ __('change_password') }}</h5>
      <div class="card-body pt-1">
        <form id="formChangePassword" method="POST" action="{{ route('admin.users.profile.update-password', $user->id) }}" data-user-id="{{ $user->id }}">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="mb-3 col-md-6 form-password-toggle">
              <label class="form-label" for="currentPassword">{{ __('old_password') }}</label>
              <div class="input-group input-group-merge">
                <input class="form-control @error('current_password') is-invalid @enderror" type="password" name="current_password" id="currentPassword" placeholder="············" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="mb-3 col-md-6 form-password-toggle">
              <label class="form-label" for="newPassword">{{ __('new_password') }}</label>
              <div class="input-group input-group-merge">
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="newPassword" name="password" placeholder="············" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3 col-md-6 form-password-toggle">
              <label class="form-label" for="confirmPassword">{{ __('confirm_password') }}</label>
              <div class="input-group input-group-merge">
                <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" id="confirmPassword" placeholder="············" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <h6 class="text-body">{{ __('password_requirements') }}</h6>
          <ul class="ps-4 mb-0">
            <li class="mb-1">{{ __('password_length_validation') }}</li>
            <li class="mb-1">{{ __('password_lower_case_rule') }}</li>
            <li>{{ __('password_special_char_rule') }}</li>
          </ul>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{ __('save_changes') }}</button>
            <button type="reset" class="btn btn-label-secondary">{{ __('reset_form') }}</button>
          </div>
        </form>
      </div>
    </div>
    <!--/ Şifre Değiştirme -->
  </div>
</div>
@endsection
