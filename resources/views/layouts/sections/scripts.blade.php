<!-- BEGIN: Vendor JS-->

@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/popper/popper.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/libs/node-waves/node-waves.js',
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
  'resources/assets/vendor/libs/hammer/hammer.js',
  'resources/assets/vendor/libs/typeahead-js/typeahead.js',
  'resources/assets/vendor/js/menu.js'
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<!-- Çeviri Yükleme JavaScript -->
<script>
  // Temel çeviriler için boş nesne oluştur
  if (!window.translations) window.translations = {};
  
  // Sabit çeviriler - DataTable için gerekli çevirileri burada tanımla
  window.translations['export'] = window.translations['export'] || 'Dışa Aktar';
  window.translations['import_language'] = window.translations['import_language'] || 'İçe Aktar';
  window.translations['add_language'] = window.translations['add_language'] || 'Yeni Dil Ekle';
  
  // Çeviri fonksiyonu tanımlı değilse oluştur
  window.__ = window.__ || function(key, replacements) {
    if (!key) return '';
    
    // Çeviriyi al, yoksa anahtarı döndür
    let translation = window.translations[key] || key;
    
    // Parametreleri değiştir (varsa)
    if (replacements) {
      for (const placeholder in replacements) {
        translation = translation.replace(':' + placeholder, replacements[placeholder]);
      }
    }
    
    return translation;
  };
  
  // Çevirileri yükleme fonksiyonu - global olarak tanımla
  window.loadTranslations = window.loadTranslations || function(translationsData) {
    if (!translationsData) return false;
    
    // Mevcut çevirilere yeni çevirileri ekle
    Object.assign(window.translations, translationsData);
    
    // Yüklenme durumunu güncelle
    window.translationsLoaded = true;
    
    // Dil tablosu varsa yenile
    if (window.refreshLanguageTable) setTimeout(window.refreshLanguageTable, 0);
    
    return true;
  };
  
  // Çevirileri yükleme fonksiyonu
  function loadTranslationsScript() {
    // Çeviri script'i önceden yüklenmişse tekrar yükleme
    if (window.translationsLoaded) return Promise.resolve();
    
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = '/translations/js?t=' + Date.now(); // Cache sorunlarını önlemek için
      script.async = false;
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }
  
  // Sayfa yüklendiğinde çevirileri yükle
  document.addEventListener('DOMContentLoaded', loadTranslationsScript);
  
  // Sayfa tamamen yüklendiğinde tabloları yenile
  window.addEventListener('load', function() {
    if (window.refreshLanguageTable) {
      setTimeout(window.refreshLanguageTable, 200);
    }
  });
</script>

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
