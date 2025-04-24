@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Giriş - Pateez Haber Admin')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/pages-auth.js'
])
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{url('/')}}" class="app-brand auth-cover-brand">
    <span class="app-brand-logo demo">@include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])</span>
    <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-8 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
        <img src="{{ asset('assets/img/illustrations/auth-login-illustration-'.$configData['style'].'.png') }}" alt="auth-login-cover" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png">

        <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
      <div class="w-px-400 mx-auto mt-12 pt-5">
        <h4 class="mb-1">Pateez Haber Admin Paneli 👋</h4>
        <p class="mb-6">Lütfen hesabınıza giriş yapın</p>

        <div class="mb-6">
          <div class="mb-6">
            <label for="email" class="form-label">E-posta veya Kullanıcı Adı</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="E-posta veya kullanıcı adınızı girin" autofocus>
          </div>
          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">Şifre</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>
          <div class="my-8">
            <div class="d-flex justify-content-between">
              <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Beni Hatırla
                </label>
              </div>
              <a href="{{url('/admin/forgot-password')}}">
                <p class="mb-0">Şifremi Unuttum?</p>
              </a>
            </div>
          </div>
          <a href="{{url('/admin/dashboard')}}" class="btn btn-primary d-grid w-100">Giriş Yap</a>
        </div>

        <p class="text-center">
          <span>Platformumuzda yeni misiniz?</span>
          <a href="{{url('/admin/register')}}">
            <span>Hesap oluşturun</span>
          </a>
        </p>

        <div class="divider my-6">
          <div class="divider-text">veya</div>
        </div>

        <div class="d-flex justify-content-center">
          <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-facebook me-1_5">
            <i class="tf-icons ti ti-brand-facebook-filled"></i>
          </a>

          <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-twitter me-1_5">
            <i class="tf-icons ti ti-brand-twitter-filled"></i>
          </a>

          <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-github me-1_5">
            <i class="tf-icons ti ti-brand-github-filled"></i>
          </a>

          <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-google-plus">
            <i class="tf-icons ti ti-brand-google-filled"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection
