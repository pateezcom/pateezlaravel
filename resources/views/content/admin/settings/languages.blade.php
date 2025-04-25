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
  'resources/assets/vendor/libs/datatables-buttons/buttons.bootstrap5.js',
  'resources/assets/vendor/libs/jszip/jszip.js',
  'resources/assets/vendor/libs/pdfmake/pdfmake.js',
  'resources/assets/vendor/libs/datatables-buttons/buttons.html5.js',
  'resources/assets/vendor/libs/datatables-buttons/buttons.print.js',
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
    <h5 class="card-title mb-0">Dil Yönetimi</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-languages table border-top" style="text-transform: capitalize !important;">
      <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Dil adı</th>
          <th>Varsayılan dil</th>
          <th>Çeviri/Dışa aktar</th>
          <th>Seçenekler</th>
        </tr>
      </thead>
    </table>
  </div>

<!-- Modallar -->
@include('content.admin._partials._modals.modal-language-add')
@include('content.admin._partials._modals.modal-language-edit')
@include('content.admin._partials._modals.modal-language-import')
<!-- /Modallar -->
@endsection

@section('page-script')
@vite([
  'resources/js/admin/settings/languages.js',
  'resources/js/admin/settings/language-form-validation.js'
])
@endsection
