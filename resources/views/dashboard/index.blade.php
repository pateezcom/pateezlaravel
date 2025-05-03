@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Pateez Haber')

@section('content')
<div class="row g-6">
  <!-- Welcome card -->
  <div class="col-md-12 col-lg-8">
    <div class="card mb-6">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Hoş Geldiniz {{ auth()->user()->name }}! 🎉</h5>
            <p class="mb-4">
              Pateez Haber yönetim paneline hoş geldiniz. Sistemde güvenli bir şekilde yönetim işlemlerinizi gerçekleştirebilirsiniz.
            </p>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Profili Görüntüle</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-6">
            <img src="{{asset('assets/img/illustrations/man-with-laptop.png')}}" height="140" alt="View Badge User">
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- User Info Card -->
  <div class="col-md-12 col-lg-4">
    <div class="card mb-6">
      <div class="card-body">
        <h5 class="card-title">Kullanıcı Bilgileri</h5>
        <ul class="list-unstyled mb-0">
          <li class="mb-3">
            <div class="d-flex align-items-center">
              <i class="ti ti-user me-2"></i>
              <span class="fw-medium">Rol:</span> {{ auth()->user()->roles->first()?->name ?? 'Belirlenmedi' }}
            </div>
          </li>
          <li class="mb-3">
            <div class="d-flex align-items-center">
              <i class="ti ti-mail me-2"></i>
              <span class="fw-medium">E-posta:</span