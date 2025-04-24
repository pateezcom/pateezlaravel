@extends('layouts/layoutMaster')

@section('title', 'Dil Ayarları')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
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
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
])
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-2">
  <span class="text-muted fw-light">Ayarlar /</span> Dil Ayarları
</h4>

<div class="row">
  <!-- Dil Listesi Tablosu -->
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-3">Diller</h5>
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-basic table border-top dataTable" id="DataTables_Table_0">
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
          <tbody>
            @foreach($languages as $language)
            <tr>
              <td></td>
              <td>{{ $language['id'] }}</td>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="d-flex flex-column">
                    <span class="fw-medium">{{ $language['name'] }}</span>
                    <small class="text-muted">{{ $language['code'] }}</small>
                  </div>
                </div>
              </td>
              <td>
                @if($language['is_default'])
                  <span class="badge bg-label-primary">Varsayılan</span>
                @else
                  <button class="btn btn-sm btn-outline-primary">Varsayılan Yap</button>
                @endif
              </td>
              <td>
                <div class="d-inline-block">
                  <button class="btn btn-sm btn-outline-info me-1">
                    <i class="ti ti-edit ti-xs me-1"></i>Çeviriler
                  </button>
                  <button class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-download ti-xs me-1"></i>Dışa Aktar
                  </button>
                </div>
              </td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical text-muted"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);">
                      <i class="ti ti-pencil me-1"></i>Düzenle
                    </a>
                    <a class="dropdown-item text-danger" href="javascript:void(0);">
                      <i class="ti ti-trash me-1"></i>Sil
                    </a>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Sonu -->

<!-- Modallar -->
@include('_partials._modals.modal-language-add')
@include('_partials._modals.modal-language-edit')
@include('_partials._modals.modal-language-import')
<!-- /Modallar -->
@endsection

@section('page-script')
@vite(['resources/js/admin/settings/languages.js'])
@endsection