<!-- Import Language Modal -->
<div class="modal fade" id="importLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <h5 class="modal-title w-100 text-center">Dil İçe Aktar</h5>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <p>JSON dil dosyasını yükleyin</p>
        </div>
        
        <form id="importLanguageForm" class="row g-3">
          <div class="col-12 mb-3">
            <label class="form-label" for="languageFile">JSON Dil Dosyası</label>
            <input class="form-control" type="file" id="languageFile">
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-3">İçe Aktar</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">İptal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Import Language Modal -->
