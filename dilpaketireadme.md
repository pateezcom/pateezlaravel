# Dil Paketi Modülü Dokümantasyonu

## Genel Bakış

Pateez Haber Projesi'nin dil paketi modülü, çok dilli web sitesi ve uygulama desteği için tasarlanmış kapsamlı bir çözümdür. Bu modül, Laravel'in mevcut çeviri mekanizmasını genişleterek veritabanı tabanlı bir çeviri sistemi sunar ve dinamik dil değişimini destekler.

> **ÖNEMLİ NOT**: Tüm çeviriler için Laravel'in yerleşik `__()` fonksiyonu kullanılmalıdır. Bu, sistemin tutarlı bir şekilde çalışması için kritik öneme sahiptir ve tüm geliştirme sürecinde bu kurala kesinlikle uyulmalıdır.

## Veritabanı Yapısı

Dil paketi modülü iki ana tabloya dayanır:

1. **languages** - Sitede desteklenen dillerin listesi
   - `id`: Benzersiz kimlik numarası
   - `name`: Dilin tam adı (örn. Türkçe, English)
   - `code`: Dil kodu (örn. tr, en)
   - `icon`: Dil için ikon sınıfı (örn. flag-icon-tr)
   - `is_rtl`: Sağdan sola yazım mı? (0/1)
   - `is_default`: Varsayılan dil mi? (0/1)
   - `is_active`: Dil aktif mi? (0/1)
   - `text_editor_lang`: Metin editörü için dil kodu
   - `created_at`: Oluşturma tarihi
   - `updated_at`: Güncelleme tarihi

2. **translations** - Çeviriler tablosu
   - `id`: Benzersiz kimlik numarası
   - `language_id`: Dil ID (languages tablosundan)
   - `group`: Çeviri grubu (örn. messages, validation)
   - `key`: Çeviri anahtarı
   - `value`: Çevirinin değeri
   - `created_at`: Oluşturma tarihi
   - `updated_at`: Güncelleme tarihi

## Dosya Yapısı ve Bileşenler

Dil paketi modülü aşağıdaki dosyaları içerir:

### Controller Sınıfları

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Controllers\Admin\Settings\Language\LanguageController.php`: 
  Admin paneli üzerinden dil CRUD işlemlerini yönetir. Dil ekleme, düzenleme, silme, içe/dışa aktarma ve varsayılan dil ayarlama fonksiyonlarını içerir.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Controllers\Admin\Settings\Language\TranslationController.php`: 
  Admin panelinde çeviri yönetimi işlemlerini yürütür. Çevirileri görüntüleme, arama, ekleme, düzenleme ve silme işlevlerini sağlar.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Controllers\Admin\Settings\Language\LanguageSwitchController.php`: 
  Kullanıcı tarafında dil değiştirme işlemlerini yönetir. Dil seçeneklerini ve kullanıcı dil tercihlerini session ve cookie üzerinde saklar.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Controllers\Admin\Settings\Language\JsTranslationController.php`: 
  JavaScript tarafında kullanılacak çevirileri yönetir. Çevirileri önbelleğe alma ve JavaScript için formatlanmış çeviri dosyası oluşturma işlemlerini yürütür.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Controllers\Admin\Settings\Language\DatabaseTranslationController.php`: 
  Çeviri önbelleğini temizleme ve belirli anahtar çevirilerini alma işlemlerini sağlayan kontrolcü.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/app/Http/Controllers/Admin/Settings/Language/LanguageController.php`: 
  Admin paneli üzerinden dil CRUD işlemlerini yönetir. Dil ekleme, düzenleme, silme, içe/dışa aktarma ve varsayılan dil ayarlama fonksiyonlarını içerir.

- `/Users/pateez/Desktop/pateezlaravel/app/Http/Controllers/Admin/Settings/Language/TranslationController.php`: 
  Admin panelinde çeviri yönetimi işlemlerini yürütür. Çevirileri görüntüleme, arama, ekleme, düzenleme ve silme işlevlerini sağlar.

- `/Users/pateez/Desktop/pateezlaravel/app/Http/Controllers/Admin/Settings/Language/LanguageSwitchController.php`: 
  Kullanıcı tarafında dil değiştirme işlemlerini yönetir. Dil seçeneklerini ve kullanıcı dil tercihlerini session ve cookie üzerinde saklar.

- `/Users/pateez/Desktop/pateezlaravel/app/Http/Controllers/Admin/Settings/Language/JsTranslationController.php`: 
  JavaScript tarafında kullanılacak çevirileri yönetir. Çevirileri önbelleğe alma ve JavaScript için formatlanmış çeviri dosyası oluşturma işlemlerini yürütür.

- `/Users/pateez/Desktop/pateezlaravel/app/Http/Controllers/Admin/Settings/Language/DatabaseTranslationController.php`: 
  Çeviri önbelleğini temizleme ve belirli anahtar çevirilerini alma işlemlerini sağlayan kontrolcü.

### Middleware

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\app\Http\Middleware\LocaleMiddleware.php`: 
  Her istek için kullanıcının dil tercihini oturumdan, cookie'den veya varsayılan dilden belirleyerek uygulayan middleware bileşeni.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/app/Http/Middleware/LocaleMiddleware.php`: 
  Her istek için kullanıcının dil tercihini oturumdan, cookie'den veya varsayılan dilden belirleyerek uygulayan middleware bileşeni.

### Servis Sağlayıcılar

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\app\Providers\TranslationServiceProvider.php`: 
  Laravel'in çeviri sistemini genişletmek için standart çeviri servisini özelleştirilmiş veritabanı tabanlı çeviri servisi ile değiştiren servis sağlayıcı.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Providers\ViewServiceProvider.php`: 
  Aktif dilleri tüm view şablonlarıyla paylaşarak dil seçeneklerinin her sayfada kullanılabilmesini sağlayan servis sağlayıcı.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/app/Providers/TranslationServiceProvider.php`: 
  Laravel'in çeviri sistemini genişletmek için standart çeviri servisini özelleştirilmiş veritabanı tabanlı çeviri servisi ile değiştiren servis sağlayıcı.

- `/Users/pateez/Desktop/pateezlaravel/app/Providers/ViewServiceProvider.php`: 
  Aktif dilleri tüm view şablonlarıyla paylaşarak dil seçeneklerinin her sayfada kullanılabilmesini sağlayan servis sağlayıcı.

### Çeviri Sınıfları

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\app\Translation\DatabaseTranslationLoader.php`: 
  Laravel'in standart dosya tabanlı çeviri yükleyicisini veritabanı çevirileriyle genişleten ve önbellek mekanizması ekleyen yükleyici sınıfı.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Translation\DatabaseTranslator.php`: 
  Laravel'in çeviri motorunu genişleterek veritabanı çevirilerini entegre eden özel çevirici sınıfı. Önbellek yönetimi ve çeviri optimizasyonu sağlar.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/app/Translation/DatabaseTranslationLoader.php`: 
  Laravel'in standart dosya tabanlı çeviri yükleyicisini veritabanı çevirileriyle genişleten ve önbellek mekanizması ekleyen yükleyici sınıfı.

- `/Users/pateez/Desktop/pateezlaravel/app/Translation/DatabaseTranslator.php`: 
  Laravel'in çeviri motorunu genişleterek veritabanı çevirilerini entegre eden özel çevirici sınıfı. Önbellek yönetimi ve çeviri optimizasyonu sağlar.

### Model Sınıfları

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\app\Models\Admin\Settings\Language\Language.php`: 
  Dil verisini temsil eden Eloquent model sınıfı. Dil özelliklerini ve çevirilerle ilişkisini tanımlar.

- `C:\Users\nasipse\Desktop\pateezlaravel\app\Models\Admin\Settings\Language\Translation.php`: 
  Çeviri verisini temsil eden Eloquent model sınıfı. Çeviri metinlerini ve dillerle ilişkisini tanımlar.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/app/Models/Admin/Settings/Language/Language.php`: 
  Dil verisini temsil eden Eloquent model sınıfı. Dil özelliklerini ve çevirilerle ilişkisini tanımlar.

- `/Users/pateez/Desktop/pateezlaravel/app/Models/Admin/Settings/Language/Translation.php`: 
  Çeviri verisini temsil eden Eloquent model sınıfı. Çeviri metinlerini ve dillerle ilişkisini tanımlar.

### Views

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\resources\views\content\admin\settings\language\languages.blade.php`: 
  Dil yönetimi arayüzü şablonu. Dilleri görüntüleme, ekleme, düzenleme ve silme işlemlerini içerir.

- `C:\Users\nasipse\Desktop\pateezlaravel\resources\views\content\admin\settings\language\translations.blade.php`: 
  Çeviri yönetimi arayüzü şablonu. Çevirileri görüntüleme, arama, ekleme, düzenleme ve silme işlemlerini içerir.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/resources/views/content/admin/settings/language/languages.blade.php`: 
  Dil yönetimi arayüzü şablonu. Dilleri görüntüleme, ekleme, düzenleme ve silme işlemlerini içerir.

- `/Users/pateez/Desktop/pateezlaravel/resources/views/content/admin/settings/language/translations.blade.php`: 
  Çeviri yönetimi arayüzü şablonu. Çevirileri görüntüleme, arama, ekleme, düzenleme ve silme işlemlerini içerir.

### Modals

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\resources\views\content\admin\_partials\_modals\modal-language-add.blade.php`: 
  Dil ekleme modal dialog şablonu.

- `C:\Users\nasipse\Desktop\pateezlaravel\resources\views\content\admin\_partials\_modals\modal-language-edit.blade.php`: 
  Dil düzenleme modal dialog şablonu.

- `C:\Users\nasipse\Desktop\pateezlaravel\resources\views\content\admin\_partials\_modals\modal-language-import.blade.php`: 
  Dil içe aktarma modal dialog şablonu.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/resources/views/content/admin/_partials/_modals/modal-language-add.blade.php`: 
  Dil ekleme modal dialog şablonu.

- `/Users/pateez/Desktop/pateezlaravel/resources/views/content/admin/_partials/_modals/modal-language-edit.blade.php`: 
  Dil düzenleme modal dialog şablonu.

- `/Users/pateez/Desktop/pateezlaravel/resources/views/content/admin/_partials/_modals/modal-language-import.blade.php`: 
  Dil içe aktarma modal dialog şablonu.

### JavaScript Dosyaları

#### Windows
- `C:\Users\nasipse\Desktop\pateezlaravel\resources\js\admin\settings\language\translations.js`: 
  Çeviri yönetimi AJAX işlemleri ve kullanıcı arayüzü etkileşimleri için JavaScript kodu.

- `C:\Users\nasipse\Desktop\pateezlaravel\resources\js\admin\settings\language\languages.js`: 
  Dil yönetimi AJAX işlemleri ve kullanıcı arayüzü etkileşimleri için JavaScript kodu.

- `C:\Users\nasipse\Desktop\pateezlaravel\resources\js\admin\settings\language\language-form-validation.js`: 
  Dil formları için doğrulama kuralları ve validasyon işlemlerini içeren JavaScript kodu.

#### macOS
- `/Users/pateez/Desktop/pateezlaravel/resources/js/admin/settings/language/translations.js`: 
  Çeviri yönetimi AJAX işlemleri ve kullanıcı arayüzü etkileşimleri için JavaScript kodu.

- `/Users/pateez/Desktop/pateezlaravel/resources/js/admin/settings/language/languages.js`: 
  Dil yönetimi AJAX işlemleri ve kullanıcı arayüzü etkileşimleri için JavaScript kodu.

- `/Users/pateez/Desktop/pateezlaravel/resources/js/admin/settings/language/language-form-validation.js`: 
  Dil formları için doğrulama kuralları ve validasyon işlemlerini içeren JavaScript kodu.

## Özellikler

### 1. Dil Yönetimi

- Yeni dil ekleme, düzenleme ve silme
- Dil durumunu aktif/pasif yapma
- Varsayılan dil ayarlama
- Dil listelerini dışa ve içe aktarma (JSON formatında)
- RTL (sağdan sola) dilleri destekleme
- DataTable entegrasyonu ile gelişmiş filtreleme ve sıralama

### 2. Çeviri Yönetimi

- Çeviri grupları oluşturma ve düzenleme
- Çevirileri anahtar-değer çiftleri şeklinde yönetme
- Çeviri arama ve filtreleme
- Eksik çevirileri otomatik tespit etme
- Çevirileri JSON veya PHP dosyalarından içe aktarma
- Çevirileri JSON formatında dışa aktarma

### 3. Dil Değiştirme

- Kullanıcı arayüzünden dil değiştirme
- SEO dostu URL yapısı (`/tr/anasayfa`, `/en/home`)
- Dil tercihini oturumda ve cookie'de saklama
- Tarayıcı diline göre otomatik dil seçimi

### 4. JavaScript Çevirileri

- JavaScript için otomatik çeviri dosyası oluşturma
- Frontend'de __() benzeri JS çeviri fonksiyonu
- Çeviri önbelleğini temizleme ve yenileme
- Dinamik dil değişimine otomatik yanıt verme

## Kullanım

### Çeviri Ekleme (Backend)

```php
// Controller veya başka bir sınıf içinde
use App\Models\Admin\Settings\Language\Translation;

$newTransKey = 'welcome_message';
Translation::create([
    'language_id' => $languageId,
    'group' => 'messages',
    'key' => $newTransKey,
    'value' => 'Hoş Geldiniz'
]);
```

### Çeviri Kullanımı (Views)

```php
// Blade şablonunda
<h1>{{ __('messages.welcome_message') }}</h1>

// JavaScript içinde (vue.js dışında)
<script>
  const welcomeText = '{{ __('messages.welcome_message') }}';
</script>
```

### JavaScript Çeviri Kullanımı

```javascript
// JavaScript dosyalarında veya inline script içinde
document.getElementById('welcome').textContent = window.translations['welcome_message'];

// Eğer JsTranslationController'ın window.__ fonksiyonu tanımlanmışsa:
document.getElementById('welcome').textContent = __('welcome_message');
```

### Vue.js Entegrasyonu

```javascript
// Vue.js bileşenlerinde (i18n ile)
<template>
  <div>
    <h1>{{ $t('messages.welcome_message') }}</h1>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log(this.$t('messages.welcome_message'));
  }
}
</script>
```

## Performans Optimizasyonları

1. **Önbellek Kullanımı**:
   - Çeviriler Redis veya file cache ile önbelleğe alınır
   - Çeviri eklendiğinde veya güncellendiğinde önbellek otomatik temizlenir
   - Üretim ortamında önbellek TTL süresi 24 saat olarak ayarlanmıştır

2. **Batch İşlemleri**:
   - Toplu çeviri içe aktarma işlemleri chunk'lara bölünerek yapılır
   - Her 500 kayıt sonrası veritabanı işlemi commit edilir

3. **Lazy Loading**:
   - Çeviriler sadece gerektiğinde yüklenir
   - Grup bazlı çeviri yüklemesi desteklenir

## Güvenlik Önlemleri

1. **Form Doğrulama**:
   - Tüm dil ve çeviri form girdileri hem client hem server tarafında doğrulanır
   - XSS ve SQL injection koruması için Laravel'in form doğrulama mekanizmaları kullanılır

2. **AJAX Güvenliği**:
   - Tüm AJAX istekleri CSRF token doğrulaması gerektirir
   - JSON yanıtlarda hata mesajları ve stack trace'ler sadece geliştirme ortamında gösterilir

3. **Yetkilendirme**:
   - Dil yönetimi fonksiyonlarına sadece yetkili kullanıcılar erişebilir
   - Hassas çeviri işlemleri (toplu silme, dışa aktarma) için ek yetkilendirme kontrolleri yapılır

## Notlar ve En İyi Uygulamalar

1. Laravel'in `__()` helper fonksiyonunu tutarlı bir şekilde kullanın
2. Çeviri anahtarlarını mantıksal gruplara ayırın (örn. auth, validation, messages)
3. Frontend için gerekli çevirileri JSON formatında tek bir dosyada dışa aktarın
4. Çeviri anahtarları için nokta gösterimi kullanın (örn. `user.profile.title`)
5. Varsayılan dil için tüm çevirilerin eksiksiz olduğundan emin olun
6. Çeviri anahtarlarını sayfalara ve modüllere göre organize edin
7. RTL dil desteği için CSS sınıflarını doğru şekilde yapılandırın
8. Üretim ortamına geçmeden önce çeviri önbelleğini ısıtın