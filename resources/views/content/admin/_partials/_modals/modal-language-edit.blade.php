<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center py-4 px-5 pb-3">
          <h4 class="mb-1">Dil Düzenle</h4>
          <p class="mb-3">Dil bilgilerini güncelleyin</p>
        </div>
        
        <form id="editLanguageForm" class="row g-3 px-5 pb-4">
          <input type="hidden" id="editLanguageId" name="editLanguageId">
          
          <div class="col-md-12 mb-2">
            <label class="form-label" for="editLanguageName">Dil Adı</label>
            <input type="text" id="editLanguageName" name="editLanguageName" class="form-control" placeholder="Örn: Türkçe">
            <div class="invalid-feedback"></div>
            <small class="text-muted">Örn: English</small>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label" for="editShortForm">Kısa Form</label>
            <input type="text" id="editShortForm" name="editShortForm" class="form-control" placeholder="Örn: tr">
            <div class="invalid-feedback"></div>
            <small class="text-muted">Örn: en</small>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label" for="editLanguageCode">Dil Kodu</label>
            <input type="text" id="editLanguageCode" name="editLanguageCode" class="form-control" placeholder="Örn: tr_TR">
            <div class="invalid-feedback"></div>
            <small class="text-muted">Örn: en_us</small>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label" for="editOrderInput">Sıra</label>
            <input type="number" id="editOrderInput" name="editOrderInput" class="form-control" value="1">
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label" for="editTextEditorLanguage">Text Editör Dili</label>
            <select id="editTextEditorLanguage" name="editTextEditorLanguage" class="form-select">
              <option value="">Seçiniz</option>
              <option value="ar">Arabic</option>
              <option value="hy">Armenian</option>
              <option value="az">Azerbaijani</option>
              <option value="eu">Basque</option>
              <option value="be">Belarusian</option>
              <option value="bn_BD">Bengali (Bangladesh)</option>
              <option value="bs">Bosnian</option>
              <option value="bg_BG">Bulgarian</option>
              <option value="ca">Catalan</option>
              <option value="zh_CN">Chinese (China)</option>
              <option value="zh_TW">Chinese (Taiwan)</option>
              <option value="hr">Croatian</option>
              <option value="cs">Czech</option>
              <option value="da">Danish</option>
              <option value="dv">Divehi</option>
              <option value="nl">Dutch</option>
              <option value="en">English</option>
              <option value="et">Estonian</option>
              <option value="fo">Faroese</option>
              <option value="fi">Finnish</option>
              <option value="fr_FR">French</option>
              <option value="gd">Gaelic, Scottish</option>
              <option value="gl">Galician</option>
              <option value="ka_GE">Georgian</option>
              <option value="de">German</option>
              <option value="el">Greek</option>
              <option value="he">Hebrew</option>
              <option value="hi_IN">Hindi</option>
              <option value="hu_HU">Hungarian</option>
              <option value="is_IS">Icelandic</option>
              <option value="id">Indonesian</option>
              <option value="it">Italian</option>
              <option value="ja">Japanese</option>
              <option value="kab">Kabyle</option>
              <option value="kk">Kazakh</option>
              <option value="km_KH">Khmer</option>
              <option value="ko_KR">Korean</option>
              <option value="ku">Kurdish</option>
              <option value="lv">Latvian</option>
              <option value="lt">Lithuanian</option>
              <option value="lb">Luxembourgish</option>
              <option value="ml">Malayalam</option>
              <option value="mn">Mongolian</option>
              <option value="nb_NO">Norwegian Bokmål (Norway)</option>
              <option value="fa">Persian</option>
              <option value="pl">Polish</option>
              <option value="pt_BR">Portuguese (Brazil)</option>
              <option value="pt_PT">Portuguese (Portugal)</option>
              <option value="ro">Romanian</option>
              <option value="ru">Russian</option>
              <option value="sr">Serbian</option>
              <option value="si_LK">Sinhala (Sri Lanka)</option>
              <option value="sk">Slovak</option>
              <option value="sl_SI">Slovenian (Slovenia)</option>
              <option value="es">Spanish</option>
              <option value="es_MX">Spanish (Mexico)</option>
              <option value="sv_SE">Swedish (Sweden)</option>
              <option value="tg">Tajik</option>
              <option value="ta">Tamil</option>
              <option value="tt">Tatar</option>
              <option value="th_TH">Thai</option>
              <option value="tr">Turkish</option>
              <option value="ug">Uighur</option>
              <option value="uk">Ukrainian</option>
              <option value="vi">Vietnamese</option>
              <option value="cy">Welsh</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label d-block">Yazı Yönü</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="editTextDirection" id="editTextDirectionLTR" value="ltr" checked>
                <label class="form-check-label" for="editTextDirectionLTR">Soldan Sağa</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="editTextDirection" id="editTextDirectionRTL" value="rtl">
                <label class="form-check-label" for="editTextDirectionRTL">Sağdan Sola</label>
              </div>
            </div>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="col-md-6 mb-2">
            <label class="form-label d-block">Durum</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="editStatus" id="editStatusActive" value="active" checked>
                <label class="form-check-label" for="editStatusActive">Aktif</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="editStatus" id="editStatusInactive" value="inactive">
                <label class="form-check-label" for="editStatusInactive">Pasif</label>
              </div>
            </div>
            <div class="invalid-feedback"></div>
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