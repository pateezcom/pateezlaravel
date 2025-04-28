'use strict';

// Çeviri nesnesi için temel tanımlama
window.translations = window.translations || {};

// Çeviri fonksiyonu
window.__ = window.__ || function (key, replacements = {}) {
  // Anahtar yoksa boş değer döndür
  if (!key) return '';
  
  // Çeviriyi al (yoksa anahtarı döndür)
  let translation = window.translations[key] || key;

  // Değişkenleri yerine koy
  for (const placeholder in replacements) {
    translation = translation.replace(`:${placeholder}`, replacements[placeholder]);
  }

  return translation;
};

// Çevirileri yükleme fonksiyonu
window.loadTranslations = window.loadTranslations || function (translationsData) {
  if (!translationsData) return false;
  
  // Çevirileri window nesnesine ekle
  Object.assign(window.translations, translationsData);
  
  // Durum bilgisini güncelle
  window.translationsLoaded = true;
  
  // Tabloları yenile
  if (window.refreshLanguageTable) {
    setTimeout(window.refreshLanguageTable, 0);
  }
  
  // Olay tetikle
  const event = new CustomEvent('translationsLoaded');
  window.dispatchEvent(event);
  
  return true;
};

