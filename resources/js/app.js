import './bootstrap';
import './helpers/translations';

// DataTable için gerekli çevirileri tanımla
window.translations = window.translations || {};

// Temel çevirileri ekle
const baseTranslations = {
  'export': 'Dışa Aktar',
  'import_language': 'İçe Aktar',
  'add_language': 'Yeni Dil Ekle',
  'languages': 'Diller',
  'language_name': 'Dil Adı',
  'default_language': 'Varsayılan Dil',
  'translation': 'Çeviri',
  'options': 'Seçenekler',
  'id': 'ID'
};

// Temel çevirileri window.translations nesnesine ekle
Object.assign(window.translations, baseTranslations);

// Sayfa yüklendiğinde çeviri ağırlıklı işlemler için event dinleyici
window.addEventListener('translationsLoaded', function() {
  // DataTable varsa yenile
  if (window.refreshLanguageTable) setTimeout(window.refreshLanguageTable, 10);
});

// Gerekli asset'leri import et
import.meta.glob(['../assets/img/**', '../assets/vendor/fonts/**']);
