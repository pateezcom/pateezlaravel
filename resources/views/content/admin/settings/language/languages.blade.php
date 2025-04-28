@extends('layouts/layoutMaster')

@section('title', 'Dil Ayarları')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss',
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
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('content')
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-0">{{ __('languages') }}</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-languages table border-top" >
      <thead>
        <tr >
          <th></th>
          <th>{{ __('id') }}</th>
          <th>{{ __('language_name') }}</th>
          <th>{{ __('default_language') }}</th>
          <th>{{ __('translation') }}/{{ __('export') }}</th>
          <th>{{ __('options') }}</th>
        </tr>
      </thead>
    </table>
  </div>

<!-- Modallar -->
@include('content.admin.settings.language._partials._modals.modal-language-add')
@include('content.admin.settings.language._partials._modals.modal-language-edit')
@include('content.admin.settings.language._partials._modals.modal-language-import')
<!-- /Modallar -->
@endsection

@section('page-script')
@vite([
  'resources/js/admin/settings/language/languages.js',
  'resources/js/admin/settings/language/language-form-validation.js'
])
@endsection
