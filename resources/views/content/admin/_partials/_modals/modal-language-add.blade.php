<!-- Add Language Modal -->
<div class="modal fade" id="addLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center py-4 px-5 pb-3">
          <h4 class="mb-1">Yeni Dil Ekle</h4>
          <p class="mb-3">Sisteme yeni bir dil ekleyin</p>
        </div>
        
        <form id="addLanguageForm" class="row g-3 px-5 pb-4">
          <div class="col-12 mb-2">
            <label class="form-label" for="languageName">Dil Adı</label>
            <input type="text" id="languageName" name="languageName" class="form-control" placeholder="Örn: Türkçe">
            <small class="text-muted">Örn: English</small>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label" for="shortForm">Kısa Form</label>
            <input type="text" id="shortForm" name="shortForm" class="form-control" placeholder="Örn: tr">
            <small class="text-muted">Örn: en</small>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label" for="languageCode">Dil Kodu</label>
            <input type="text" id="languageCode" name="languageCode" class="form-control" placeholder="Örn: tr_TR">
            <small class="text-muted">Örn: en_us</small>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label" for="orderInput">Sıra</label>
            <input type="number" id="orderInput" name="orderInput" class="form-control" value="1">
          </div>
          <div class="col-12 mb-2">
            <label class="form-label" for="textEditorLanguage">Text Editör Dili</label>
            <select id="textEditorLanguage" class="form-select select2">
              <option value="">Seçiniz</option>
              <option value="en">İngilizce</option>
              <option value="tr">Türkçe</option>
              <option value="ar">Arapça</option>
            </select>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label">Yazı Yönü</label>
            <div class="form-check mt-1">
              <input class="form-check-input" type="radio" name="textDirection" id="textDirectionLTR" checked="">
              <label class="form-check-label" for="textDirectionLTR">
                Soldan Sağa
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="textDirection" id="textDirectionRTL">
              <label class="form-check-label" for="textDirectionRTL">
                Sağdan Sola
              </label>
            </div>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label">Durum</label>
            <div class="form-check mt-1">
              <input class="form-check-input" type="radio" name="status" id="statusActive" checked="">
              <label class="form-check-label" for="statusActive">
                Aktif
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="status" id="statusInactive">
              <label class="form-check-label" for="statusInactive">
                Pasif
              </label>
            </div>
          </div>
          <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary me-3">Kaydet</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">İptal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Language Modal -->
