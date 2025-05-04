//app.js kodları

import './bootstrap';
import './helpers/translations';
import './app-helpers'; // Standardize edilmiş mesaj fonksiyonları

// DataTable için gerekli çevirileri tanımla
window.translations = window.translations || {};

// Sayfa yüklendiğinde çeviri ağırlıklı işlemler için event dinleyici
window.addEventListener('translationsLoaded', function () {
  // DataTable varsa yenile
  if (window.refreshLanguageTable) setTimeout(window.refreshLanguageTable, 10);
  if (window.refreshUserTable) setTimeout(window.refreshUserTable, 10);
});

// Gerekli asset'leri import et
import.meta.glob(['../assets/img/**', '../assets/vendor/fonts/**']);
