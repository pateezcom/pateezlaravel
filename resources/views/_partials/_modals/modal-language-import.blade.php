<!-- Import Language Modal -->
<div class="modal fade" id="importLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="mb-2">Dil İçe Aktar</h4>
          <p>JSON dil dosyasını yükleyin</p>
        </div>
        
        <form id="importLanguageForm" class="row g-3">
          <div class="col-12 mb-4">
            <label class="form-label" for="languageFile">JSON Dil Dosyası</label>
            <input class="form-control" type="file" id="languageFile">
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">İçe Aktar</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">İptal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Import Language Modal -->
