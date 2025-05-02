@extends('layouts/layoutMaster')

@php
$activeMenu = 'admin.users';
@endphp

@section('title', $user->name . ' - ' . __('update_profile'))

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/toastr/toastr.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/toastr/toastr.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/js/admin/users/user-profile-form-validation.js'
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
          <a class="nav-link active" href="javascript:void(0);">
            <i class="ti-sm ti ti-users me-1"></i> {{ __('account_details') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.users.profile.security', $user->id) }}">
            <i class="ti-sm ti ti-lock me-1"></i> {{ __('security') }}
          </a>
        </li>
      </ul>
    </div>

    <div class="card mb-4">
      <!-- Profil Fotoğrafı -->
      <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
          <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
          <div class="button-wrapper d-flex flex-column justify-content-center">
            <div class="d-flex gap-2 mb-2">
              <form action="{{ route('admin.users.profile.update-photo', $user->id) }}" method="POST" enctype="multipart/form-data" id="formProfilePhoto">
                @csrf
                @method('PUT')
                <label for="profile_photo" class="btn btn-primary" tabindex="0">
                  <span class="d-none d-sm-block">{{ __('upload_new_photo') }}</span>
                  <i class="ti ti-upload d-block d-sm-none"></i>
                  <input type="file" id="profile_photo" name="profile_photo" class="account-file-input" hidden accept="image/png, image/jpeg, image/jpg, image/gif" />
                </label>
                <button type="submit" class="btn btn-primary d-none" id="submitProfilePhoto">{{ __('upload') }}</button>
              </form>

              <form action="{{ route('admin.users.profile.delete-photo', $user->id) }}" method="POST" id="formDeletePhoto">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-label-secondary">
                  <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">{{ __('delete_photo') }}</span>
                </button>
              </form>
            </div>

            <div class="text-muted">{{ __('allowed_image_formats') }}</div>
          </div>
        </div>
      </div>

      <hr class="my-0" />

      <!-- Hesap Bilgileri -->
      <div class="card-body pt-2 pb-2">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
          <h5 class="mb-0">{{ __('account_details') }}</h5>
        </div>
        <form id="formAccountSettings" method="POST" action="{{ route('admin.users.profile.update-account', $user->id) }}" data-user-id="{{ $user->id }}">
          @csrf
          @method('PUT')
          <div class="row mt-2 mb-2">
            <div class="mb-3 col-md-6">
              <label for="name" class="form-label">{{ __('full_name') }}</label>
              <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus required />
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="username" class="form-label">{{ __('username') }}</label>
              <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required />
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="slug" class="form-label">{{ __('slug') }}</label>
              <input class="form-control @error('slug') is-invalid @enderror" type="text" id="slug" name="slug" value="{{ old('slug', $user->slug) }}" placeholder="{{ __('slug_auto_generation') }}" />
              @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">{{ __('email') }}</label>
              <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="{{ __('enter_email') }}" required />
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="phone" class="form-label">{{ __('phone') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+90xxxxxxxxxx" />
              </div>
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-12">
              <label for="about_me" class="form-label">{{ __('about_me') }}</label>
              <textarea class="form-control @error('about_me') is-invalid @enderror" id="about_me" name="about_me" rows="4">{{ old('about_me', $user->about_me) }}</textarea>
              @error('about_me')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">{{ __('social_accounts') }}</h5>
          </div>

          <div class="row mt-2">
            <div class="mb-3 col-md-6">
              <label for="facebook" class="form-label">{{ __('facebook') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-facebook"></i></span>
                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}" placeholder="https://facebook.com/kullanici" />
                @error('facebook')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="twitter" class="form-label">{{ __('twitter') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-twitter"></i></span>
                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}" placeholder="https://twitter.com/kullanici" />
                @error('twitter')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="instagram" class="form-label">{{ __('instagram') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-instagram"></i></span>
                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}" placeholder="https://instagram.com/kullanici" />
                @error('instagram')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="tiktok" class="form-label">{{ __('tiktok') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-tiktok"></i></span>
                <input type="url" class="form-control @error('tiktok') is-invalid @enderror" id="tiktok" name="tiktok" value="{{ old('tiktok', $user->tiktok) }}" placeholder="https://tiktok.com/@kullanici" />
                @error('tiktok')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="youtube" class="form-label">{{ __('youtube') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-youtube"></i></span>
                <input type="url" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="{{ old('youtube', $user->youtube) }}" placeholder="https://youtube.com/c/kanal" />
                @error('youtube')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="whatsapp" class="form-label">{{ __('whatsapp') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-whatsapp"></i></span>
                <input type="url" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="https://wa.me/905XXXXXXXXX" />
                @error('whatsapp')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="telegram" class="form-label">{{ __('telegram') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-telegram"></i></span>
                <input type="url" class="form-control @error('telegram') is-invalid @enderror" id="telegram" name="telegram" value="{{ old('telegram', $user->telegram) }}" placeholder="https://t.me/kullanici" />
                @error('telegram')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="discord" class="form-label">{{ __('discord') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-discord"></i></span>
                <input type="url" class="form-control @error('discord') is-invalid @enderror" id="discord" name="discord" value="{{ old('discord', $user->discord) }}" placeholder="https://discord.gg/XXXXX" />
                @error('discord')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="pinterest" class="form-label">{{ __('pinterest') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-pinterest"></i></span>
                <input type="url" class="form-control @error('pinterest') is-invalid @enderror" id="pinterest" name="pinterest" value="{{ old('pinterest', $user->pinterest) }}" placeholder="https://pinterest.com/kullanici" />
                @error('pinterest')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="linkedin" class="form-label">{{ __('linkedin') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-linkedin"></i></span>
                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/kullanici" />
                @error('linkedin')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="twitch" class="form-label">{{ __('twitch') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-twitch"></i></span>
                <input type="url" class="form-control @error('twitch') is-invalid @enderror" id="twitch" name="twitch" value="{{ old('twitch', $user->twitch) }}" placeholder="https://twitch.tv/kullanici" />
                @error('twitch')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="vk" class="form-label">{{ __('vk') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-brand-vk"></i></span>
                <input type="url" class="form-control @error('vk') is-invalid @enderror" id="vk" name="vk" value="{{ old('vk', $user->vk) }}" placeholder="https://vk.com/kullanici" />
                @error('vk')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="personal_website_url" class="form-label">{{ __('personal_website_url') }}</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-world"></i></span>
                <input type="url" class="form-control @error('personal_website_url') is-invalid @enderror" id="personal_website_url" name="personal_website_url" value="{{ old('personal_website_url', $user->personal_website_url) }}" placeholder="https://website.com" />
                @error('personal_website_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mt-4 mb-4">
            <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('save_changes') }}</button>
          </div>
        </form>
      </div>
    </div>


    <div class="card">
      <h5 class="card-header">{{ __('delete_account') }}</h5>
      <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading mb-1">{{ __('confirm_delete_account') }}</h6>
            <p class="mb-0">{{ __('delete_account_warning') }}</p>
          </div>
        </div>
        <form id="formAccountDeactivation" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="form-check my-3">
            <input class="form-check-input" type="checkbox" name="confirm_delete" id="confirm_delete" required />
            <label class="form-check-label" for="confirm_delete">{{ __('confirm_deletion_checkbox') }}</label>
          </div>
          <button type="submit" class="btn btn-danger deactivate-account">{{ __('delete_account') }}</button>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
