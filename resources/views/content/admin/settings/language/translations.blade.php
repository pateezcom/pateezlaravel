
@extends('layouts/layoutMaster')

@section('title', __('edit_translations') . ' - ' . $language->name)

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/animate-css/animate.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
@vite([
  'resources/js/admin/settings/language/translations.js'
])
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">{{ __('edit_translations') }} - {{ $language->name }}</h5>
    <a href="{{ route('admin.settings.languages') }}" class="btn btn-label-secondary">
      <i class="ti ti-arrow-left me-1"></i>{{ __('back') }}
    </a>
  </div>
  <div class="card-body">
    <!-- Arama ve filtreleme -->
    <form action="{{ route('admin.settings.translations.search', $language->id) }}" method="GET" class="mb-4 row g-2">
      <div class="col-md-3">
        <label class="form-label">{{ __('show') }}</label>
        <select name="show" class="form-select" onchange="this.form.submit()">
          <option value="10" {{ request('show', 50) == 10 ? 'selected' : '' }}>10</option>
          <option value="25" {{ request('show', 50) == 25 ? 'selected' : '' }}>25</option>
          <option value="50" {{ request('show', 50) == 50 ? 'selected' : '' }}>50</option>
          <option value="100" {{ request('show', 50) == 100 ? 'selected' : '' }}>100</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">{{ __('search') }}</label>
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="{{ __('search') }}..." value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary">{{ __('filter') }}</button>
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">{{ __('options') }}</label>
        <button type="button" class="btn btn-primary btn-add-translation w-100" data-bs-toggle="modal" data-bs-target="#addTranslationModal">
          <i class="ti ti-plus me-1"></i>{{ __('add_new_translation') }}
        </button>
      </div>
    </form>

    <!-- Çeviri formu -->
    <form id="translationForm" action="{{ route('admin.settings.translations.update', $language->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th width="5%">#</th>
              <th width="30%">{{ __('translation_key') }}</th>
              <th width="65%">{{ __('translation') }}</th>
            </tr>
          </thead>
          <tbody>
            @if($translations->count() > 0)
              @foreach($translations as $index => $translation)
                <tr>
                  <td>{{ $translations->firstItem() + $index }}</td>
                  <td>
                    <code>{{ $translation->key }}</code>
                    <input type="hidden" name="keys[]" value="{{ $translation->key }}">
                  </td>
                  <td>
                    <div class="input-group">
                      <input type="text" class="form-control translation-input"
                             name="translations[{{ $translation->key }}]"
                             value="{{ $translation->value }}"
                             data-original-value="{{ $translation->value }}">
                      <button type="button" class="btn btn-outline-danger btn-delete-translation"
                              data-id="{{ $translation->id }}"
                              data-key="{{ $translation->key }}">
                        <i class="ti ti-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center">{{ __('no_records_found') }}</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Sayfalama -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
          {{ __('table_pagination_info', ['_TOTAL_' => $translations->total(), '_START_' => $translations->firstItem() ?? 0, '_END_' => $translations->lastItem() ?? 0]) }}
        </div>
        <div class="demo-inline-spacing">
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
              <li class="page-item first {{ $translations->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $translations->url(1) }}">
                  <i class="ti ti-chevrons-left ti-xs"></i>
                </a>
              </li>
              <li class="page-item prev {{ $translations->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $translations->previousPageUrl() }}">
                  <i class="ti ti-chevron-left ti-xs"></i>
                </a>
              </li>

              @php
                $startPage = max(1, $translations->currentPage() - 2);
                $endPage = min($startPage + 4, $translations->lastPage());
                if ($endPage - $startPage < 4 && $startPage > 1) {
                    $startPage = max(1, $endPage - 4);
                }
              @endphp

              @for ($i = $startPage; $i <= $endPage; $i++)
                <li class="page-item {{ $i == $translations->currentPage() ? 'active' : '' }}">
                  <a class="page-link" href="{{ $translations->url($i) }}">{{ $i }}</a>
                </li>
              @endfor

              <li class="page-item next {{ !$translations->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $translations->nextPageUrl() }}">
                  <i class="ti ti-chevron-right ti-xs"></i>
                </a>
              </li>
              <li class="page-item last {{ $translations->currentPage() == $translations->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $translations->url($translations->lastPage()) }}">
                  <i class="ti ti-chevrons-right ti-xs"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Kaydet butonu -->
      <div class="text-end mt-4">
        <button type="submit" id="saveChangesBtn" class="btn btn-primary">
          <i class="ti ti-device-floppy me-1"></i>{{ __('save_changes') }}
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Yeni Çeviri Ekleme Modal -->
<div class="modal fade" id="addTranslationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('add_new_translation') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addTranslationForm">
          <input type="hidden" name="language_id" value="{{ $language->id }}">
          <div class="mb-3">
            <label class="form-label" for="translationKey">{{ __('translation_key') }}</label>
            <input type="text" class="form-control" id="translationKey" name="key" placeholder="örn: welcome_message" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="translationValue">{{ __('translation_value') }}</label>
            <input type="text" class="form-control" id="translationValue" name="value" placeholder="örn: Hoş Geldiniz" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="translationGroup">{{ __('translation_group') }}</label>
            <input type="text" class="form-control" id="translationGroup" name="group" placeholder="örn: messages" value="default">
            <div class="form-text">{{ __('group_default_note') }}</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveNewTranslation">{{ __('save') }}</button>
      </div>
    </div>
  </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('delete_translation') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="deleteTranslationId">
        <p class="mb-0">{{ __('confirm_delete_translation') }}</p>
        <p class="mb-0 font-weight-bold"><code id="deleteTranslationKey"></code></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">{{ __('delete') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection
