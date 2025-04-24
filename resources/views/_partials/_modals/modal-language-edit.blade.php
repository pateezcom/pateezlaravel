<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="role-title mb-2">Dil Düzenle</h4>
          <p>Dil bilgilerini güncelleyin</p>
        </div>
        
        <form id="editLanguageForm" class="row g-3">
          <input type="hidden" id="editLanguageId" name="editLanguageId">
          <div class="col-12 mb-4">
            <label class="form-label" for="editLanguageName">Dil Adı</label>
            <input type="text" id="editLanguageName" name="editLanguageName" class="form-control" placeholder="Örn: Türkçe">
            <small class="text-muted">Örn: English</small>
          </div>
          <div class="col-12 mb-4">
            <label class="form-label" for="editShortForm">Kısa Form</label>
            <input type="text" id="editShortForm" name="editShortForm" class="form-control" placeholder="Örn: tr">
            <small class="text-muted">Örn: en</small>
          </div>
          <div class="col-12 mb-4">
            <label class="form-label" for="editLanguageCode">Dil Kodu</label>
            <input type="text" id="editLanguageCode" name="editLanguageCode" class="form-control" placeholder="Örn: tr_TR">
            <small class="text-muted">Örn: en_us</small>
          </div>
          <div class="col-12 mb-4">
            <label class="form-label" for="editOrderInput">Sıra</label>
            <input type="number" id="editOrderInput" name="editOrderInput" class="form-control" value="1">
          </div>
          <div class="col-12 mb-4">
            <label class="form-label" for="editTextEditorLanguage">Text Editör Dili</label>
            <select id="editTextEditorLanguage" class="form-select select2">
              <option value="">Seçiniz</option>
              <option value="en">İngilizce</option>
              <option value="tr">Türkçe</option>
              <option value="ar">Arapça</option>
            </select>
          </div>
          <div class="col-12 mb-4">
            <label class="form-label">Yazı Yönü</label>
            <div class="form-check mt-3">
              <input class="form-check-input" type="radio" name="editTextDirection" id="editTextDirectionLTR" checked="">
              <label class="form-check-label" for="editTextDirectionLTR">
                Soldan Sağa
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="editTextDirection" id="editTextDirectionRTL">
              <label class="form-check-label" for="editTextDirectionRTL">
                Sağdan Sola
              </label>
            </div>
          </div>
          <div class="col-12 mb-4">
            <label class="form-label">Durum</label>
            <div class="form-check mt-3">
              <input class="form-check-input" type="radio" name="editStatus" id="editStatusActive" checked="">
              <label class="form-check-label" for="editStatusActive">
                Aktif
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="editStatus" id="editStatusInactive">
              <label class="form-check-label" for="editStatusInactive">
                Pasif
              </label>
            </div>
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Güncelle</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">İptal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Language Modal -->
