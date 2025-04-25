# Pateez Haber Projesi

Bu proje, Vuexy teması kullanarak Laravel backend ve Vue.js frontend ile geliştirilen modern bir haber ve buzz scripti uygulamasıdır.

## Proje Yapısı ve Mimari

Proje üç katmanlı bir mimariye sahiptir:

1. **Admin Panel**: Laravel + HTML - Admin işlemleri için kullanılır
2. **Frontend Website**: Vue.js - Kullanıcıların göreceği ön yüz
3. **Mobil Uygulama**: Flutter - iOS ve Android için native uygulama (Henüz başlanmadı)

> **ÖNEMLİ NOT**: Vue.js **SADECE** ön yüzde kullanılacaktır. Admin panelinde Vue.js kullanılmayacak, Laravel + HTML yapısı korunacaktır.

## Şimdiye Kadar Yapılanlar

### 1. Temel Kurulum ve Yapılandırma

- Vuexy temasının Laravel + HTML full versiyonu kurulumu
- Vue.js entegrasyonu ve frontend için yapılandırma
- Vite konfigürasyonu ve gerekli paketlerin kurulumu
- Bootstrap ve Tabler Icons CDN entegrasyonu

### 2. URL ve Yönlendirme Yapısı

- `/` → Ana sayfa (Vue.js ön yüz)
- `/home` → Ana sayfa alternatif link
- `/about` → Hakkımızda sayfası
- `/admin` → Admin panel login sayfasına yönlendirme
- `/admin/login` → Login sayfası (Cover tasarım)
- `/admin/dashboard` → Admin panel ana sayfa
- `/admin/settings/languages` → Dil ayarları sayfası

### 3. Ön Yüz Yapısı

- Ana sayfa ve Hakkımızda sayfaları Vue.js ile hazırlandı
- Router yapılandırması tamamlandı
- Responsive tasarım CSS ayarları yapıldı

### 4. Admin Panel

- Login sayfası Türkçeleştirildi ve düzenlendi
- Dashboard sayfası başlığı düzenlendi
- URL yapısı daha profesyonel hale getirildi
- Settings menü kategorisi ve Languages alt menüsü eklendi
- Dil Yönetimi için tablo, ekleme, düzenleme ve içe aktarma modülleri oluşturuldu

## Kullanılan Teknolojiler

- **Backend**: PHP 8.x, Laravel Framework
- **Frontend**: Vue.js 3.x, Vue Router, Bootstrap 5
- **Admin Panel**: Laravel Blade, HTML, CSS, JavaScript
- **Build Tool**: Vite

## Klasör Yapısı

- `resources/js/frontend/` → Vue.js frontend uygulaması
- `resources/js/frontend/views/` → Vue.js sayfa bileşenleri
- `resources/js/frontend/router/` → Vue Router yapılandırması
- `resources/views/content/` → Laravel Blade şablonları (Admin Panel)
- `resources/views/content/admin/settings/` → Admin panel ayarlar modülü şablonları
- `resources/views/content/admin/_partials/_modals/` → Admin panel modal şablonları
- `app/Http/Controllers/` → Laravel kontrolcüleri
- `app/Http/Controllers/Admin/Settings/` → Admin panel ayarlar kontrolcüleri
- `resources/js/admin/settings/` → Admin panel ayarlar modülü JavaScript dosyaları

## Notlar

- Her modül önce admin panelde geliştirildikten sonra frontend Vue.js ile entegre edilecek
- Tüm modüller için API'lar yazılacak (Flutter uygulaması için de kullanılacak)
- Admin panelde kesinlikle HTML + Laravel yapısı korunacak

> **ÖNEMLİ NOT**: Rota tanımlamalarını yaparken sadece `web.php` dosyasındaki `/* ========== PATEEZ NEWS ROTALAR BAŞLANGIÇ ========== */` ve `/* ========== PATEEZ NEWS ROTALAR BİTİŞ ========== */` blokları arasına ekleme yapılacaktır. Var olan diğer rotalara dokunulmayacaktır.

## Kod Standartları

Proje geliştirme sürecinde aşağıdaki standartlara uyulacaktır:

- **Kod Açıklamaları**: Tüm fonksiyonlar ve kod blokları için önce İngilizce, sonra Türkçe olarak açıklama yazılacaktır. Örnek:

```php
/**
 * Get all news items from the database with pagination
 * Veritabanından tüm haber öğelerini sayfalama ile alır
 *
 * @param int $perPage
 * @return Collection
 */
public function getAllNews($perPage = 10)
{
    // Implementation
    // Uygulama kodu
}
```

- **Değişken İsimleri**: Anlaşılır ve tutarlı isimlendirme yapılacaktır
- **Girintileme**: Laravel ve Vue.js standartlarına uygun olacaktır

## Geliştirme Süreci ve Talimatlar

1. Admin panel modülü geliştirme ("admini yap" talimatı ile)
2. Frontend Vue.js entegrasyonu ("ön paneli yap" talimatı ile)
3. Mobil uygulama entegrasyonu ("flutter'ı yap" talimatı ile)

**ÖNEMLİ NOT**: Her aşamada bir sonraki adıma geçmek için açık talimat beklenecektir. Örneğin admin paneli tamamlandıktan sonra, frontend geliştirmesine başlamak için "ön paneli yap" talimatı beklenecektir. Açık bir talimat olmadan bir sonraki aşamaya geçilmeyecektir.

**Modül Güncellemeleri**: Bir modülü bitirdiğimizde "readme yi güncelle" talimatı verilecek ve o gün eklenen özelliklerle birlikte tarih/saat bilgisi README'ye eklenecektir.

## Modül Güncellemeleri

### Son Güncelleme: 2025-04-25 14:30

- Dil yönetimi form doğrulama ve tablo yenileme işlemleri geliştirildi:
  - `/resources/js/admin/settings/language-form-validation.js` - Form doğrulama ve hata mesajları için yeni JavaScript dosyası eklendi
  - `/resources/js/admin/settings/languages.js` - Tablo yenileme fonksiyonu global scope'a alındı ve iyileştirildi
  - `/app/Http/Controllers/Admin/Settings/LanguageController.php` - Benzersizlik kontrolü için yeni metod eklendi
  - `/routes/web.php` - Benzersizlik kontrolü için `/admin/settings/languages/check-unique` rotası eklendi

- Dil yönetimi özelliklerine eklenenler:
  - Form doğrulama entegrasyonu (FormValidation.io kütüphanesi kullanılarak)
  - Dil adı, kısa form ve dil kodu için benzersizlik kontrolü
  - Form alanları için anında doğrulama ve hata mesajları
  - Tablo yenileme fonksiyonu iyileştirilmesi
  - Modal kapatma ve sayfa yenileme işlemi iyileştirildi

### Güncelleme: 2025-04-24 21:45

- Dil yönetimi modülü, Vuexy tema standardına uygun olarak yeniden düzenlendi:
  - `/resources/views/content/admin/settings/languages.blade.php` - DataTable yapısıyla güncellendi
  - `/resources/js/admin/settings/languages.js` - Vuexy DataTable standardına uygun JavaScript kodu yazıldı
  - `/app/Http/Controllers/Admin/Settings/LanguageController.php` - AJAX isteklerini destekleyecek şekilde güncellemeler yapıldı
  - `/routes/web.php` - Tüm CRUD işlemleri için gereken rotalar eklendi

- Dil yönetimi özelliklerine eklenenler:
  - DataTable entegrasyonu (arama, sayfalama, sıralama)
  - Excel, PDF, CSV ve yazdırma dışa aktarımları
  - Dil ekle/sil/düzenle fonksiyonlarının AJAX ile çalışması
  - SweetAlert2 ile bildirim ve onay modalları
  - Responsive tasarım ile mobil uyumluluğu

### Güncelleme: 2025-04-24 19:30

- Dil yönetimi için modallar oluşturuldu:
  - `/resources/views/content/admin/_partials/_modals/modal-language-add.blade.php` - Dil ekleme modal penceresi
  - `/resources/views/content/admin/_partials/_modals/modal-language-edit.blade.php` - Dil düzenleme modal penceresi
  - `/resources/views/content/admin/_partials/_modals/modal-language-import.blade.php` - Dil içe aktarma modal penceresi
- Admin navbar ve menü yapılandırmasında Settings bölümü eklendi:
  - `/resources/menu/verticalMenu.json` - Settings menü kategorisi ve Languages alt menüsü
- Language Controller oluşturuldu ve dil listesi için temel yapı hazırlandı:
  - `/app/Http/Controllers/Admin/Settings/LanguageController.php` - Dil yönetimi kontrolcüsü
- Web rotası tanımlandı:
  - `/routes/web.php` - `/admin/settings/languages` rotası eklendi
- Admin panelinde dil ayarları görünümü oluşturuldu:
  - `/resources/views/content/admin/settings/languages.blade.php` - Dil listesi ve yönetim tablosu

### Güncelleme: 2025-04-24 18:10

- Dil paketi modülü arayüzü tasarlandı
- Settings menü kategorisi ve Languages alt menüsü eklendi
- Dil ayarları için görünüm (view) ve denetleyici (controller) dosyaları oluşturuldu
- web.php için özel Pateez News rotaları bölümü oluşturuldu

### Güncelleme: 2025-04-24 15:35

- Proje temel yapısı kuruldu
- Admin ve frontend rotaları ayarlandı
- Vue.js ön yüz entegrasyonu tamamlandı
- Admin giriş sayfası düzenlendi

## Form Doğrulama İşlemi Notları

Form doğrulama işlemleri için aşağıdaki standartlara uyulmalıdır:

1. **Form Doğrulama Yapısı**:
   - FormValidation.io kütüphanesi kullanılmalıdır
   - Form doğrulama kuralları ayrı bir JavaScript dosyasında tanımlanmalıdır
   - Client-side ve server-side doğrulama birlikte kullanılmalıdır

2. **Hata Mesajlarının Gösterimi**:
   - Form alanlarının altında `.invalid-feedback` sınıfına sahip div ile hata mesajları gösterilmelidir
   - Server tarafından gelen hata mesajları doğrudan ilgili alanlara atanmalıdır

3. **Tablo Yenileme İşlemi**:
   - Tablo yenileme fonksiyonu global scope'da tanımlanmalıdır (`window.refreshLanguageTable`)
   - AJAX işlemleri sonrasında tablo otomatik olarak yenilenmelidir
   - Modal kapatma işlemi modal doğrudan yenileme işleminden önce yapılmalıdır

4. **Benzersizlik Kontrolü**:
   - Dil adı, kısa form ve dil kodu için benzersizlik kontrolü yapılmalıdır
   - Düzenleme işleminde mevcut kaydın ID'si hariç tutulmalıdır
   - Benzersizlik kontrolü için özel bir API endpoint kullanılmalıdır

## Vuexy Tabloları Kullanım Notları

Vuexy temasında DataTable kullanırken aşağıdaki standartlara uyulmalıdır:

1. **DataTable Sınıflandırması**:
   - Tablolarda `.datatables-xxx` sınıfı kullanılmalıdır (örn. `.datatables-languages`)
   - Tablo `.table` ve `.border-top` sınıflarıyla stillendirilmelidir
   - Tüm tablolar responsive olması için `.table-responsive` ile sarmalanmalıdır

2. **DOM Yapısı**:
   ```javascript
   dom: '<"row g-2"<"col-md-3"l><"col-md-6 d-flex align-items-center justify-content-center"B><"col-md-3"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
   ```
   - Arama kutusu, gösterim sayısı ve butonlar üstte
   - Sayfalama ve bilgi satırı altta

3. **Butonlar ve Dışa Aktarımlar**:
   - Tüm tablolarda Excel, PDF, CSV, yazdırma ve kopyalama düğmeleri standart olarak eklenmelidir
   - CRUD işlemleri için modal veya offcanvas kullanılmalıdır

4. **Controller AJAX Desteği**:
   - Tüm controller'lar hem normal sayfa yüklenişini hem de AJAX isteklerini desteklemelidir:
   ```php
   if ($request->ajax()) {
       // AJAX isteği için veri hazırla
       return response()->json(['data' => $data]);
   }
   ```

## README Güncelleme Notları

README.md dosyası güncellenirken:
- Yeni eklenen dosyaların tam yolu belirtilmelidir
- Yapılan değişiklikler tarih ve saat bilgisiyle birlikte eklenmelidir
- Güncellemeler en üstte olacak şekilde sıralanmalıdır
- Klasör yapısı güncellenmelidir
- Kullanım notları (örn. Vuexy Tabloları Kullanım Notları) eklenmelidir

## Yeni Sohbet Başlatma

Yeni bir sohbet başlatıldığında, bu README.md dosyasını ve GitHub repo linkini paylaşmanız yeterli olacaktır. Herhangi bir ek açıklamaya gerek kalmadan çalışmaya devam edebiliriz. Önceki bağlamı tamamen anlayarak "Konuları anladım, hazırım." mesajıyla devam edilecektir.
