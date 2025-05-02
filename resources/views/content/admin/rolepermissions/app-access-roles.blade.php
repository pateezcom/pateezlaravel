@php
$configData = Helper::appClasses();
$activeMenu = 'admin.role.permissions';
@endphp

@extends('layouts/layoutMaster')

@section('title', __('roles_permissions'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/toastr/toastr.scss'
  ])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/toastr/toastr.js'
  ])
@endsection

@section('page-script')
<script>
  // Backend URL'i tanımla
  window.baseUrl = '{{ url("/") }}/';
</script>
@vite([
  'resources/js/admin/role-permissions/role-permissions-form-validation.js',
  ])
@endsection

@section('content')
<h4 class="mb-1">{{ __('roles_list') }}</h4>

<p class="mb-6">{{ __('roles_description') }}</p>

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

<!-- Role cards -->
<div class="row g-4">
  @foreach($roles ?? [] as $role)
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h6 class="fw-normal mb-0 text-body">{{ __('total') }} {{ $roleUserCounts[$role->name] ?? 0 }} {{ __('users') }}</h6>
          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
            @php $avatarCount = 0; @endphp
            @foreach($users->filter(function($user) use ($role) { return $user->hasRole($role->name); })->take(3) as $user)
              <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar pull-up">
                <img class="rounded-circle" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
              </li>
              @php $avatarCount++; @endphp
            @endforeach
            
            @php $moreUsers = ($roleUserCounts[$role->name] ?? 0) - $avatarCount; @endphp
            @if($moreUsers > 0)
            <li class="avatar">
              <span class="avatar-initial rounded-circle pull-up" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $moreUsers }} {{ __('more') }}">+{{ $moreUsers }}</span>
            </li>
            @endif
          </ul>
        </div>
        <div class="d-flex justify-content-between align-items-end">
          <div class="role-heading">
            <h5 class="mb-1">{{ $role->name }}</h5>
            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#addRoleModal" class="role-edit-modal" 
              data-id="{{ $role->id }}" 
              data-name="{{ $role->name }}" 
              data-permissions="{{ json_encode($role->permissions->pluck('name')) }}">
              <span>{{ __('edit_role') }}</span>
            </a>
          </div>
          @if(!in_array(strtolower($role->name), ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin']))
          <a href="javascript:void(0);" class="delete-role" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
            <i class="ti ti-trash ti-md text-danger"></i>
          </a>
          @else
          <span class="text-muted opacity-0"><i class="ti ti-lock ti-md"></i></span>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endforeach
  
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="row h-100">
        <div class="col-sm-5">
          <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
            <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83">
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">{{ __('add_new_role') }}</button>
            <p class="mb-0">{{ __('add_new_role_description') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Role cards -->

<!-- Add Role Modal -->
@include('content.admin.rolepermissions._partials._modals.modal-add-role')
<!-- / Add Role Modal -->
@endsection