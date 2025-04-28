<!-- Import Language Modal -->
<div class="modal fade" id="importLanguageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <h5 class="modal-title w-100 text-center">{{ __('placeholder_search_language') }}</h5>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <p>{{ __('upload_json_language_file') }}</p>
        </div>

        <form id="importLanguageForm" class="row g-3" enctype="multipart/form-data">
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="languageFile">{{ __('json_language_file') }}</label>
            <input class="form-control" type="file" id="languageFile" name="languageFile" accept=".json">
            <div class="invalid-feedback"></div>
            <small class="text-muted">{{ __('msg_invalid_json_file') }}</small>
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" id="importLanguageSubmitBtn" class="btn btn-primary me-3">{{ __('import') }}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{ __('cancel') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Import Language Modal -->