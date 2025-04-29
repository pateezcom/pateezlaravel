# Kullanıcı Yönetimi ve Rol/İzin Sistemi Modülü

Bu modül, Pateez Haber Projesi için Laravel Jetstream ve Spatie Laravel-Permission paketleri kullanılarak geliştirilmiş, Vuexy temasıyla tam entegre edilmiş bir kullanıcı yönetimi ve rol/izin sistemi sunar.

## Model Yapısı

Kullanıcı yönetimi için aşağıdaki model dosyaları oluşturulmuştur:

- `App\Models\Admin\Users\User`: Laravel'in kullanıcı modeli, `HasRoles` trait'i eklenerek Spatie Permission paketi ile entegre edildi
- `App\Models\Admin\Users\UserRole`: Kullanıcı rol ilişkilerini yöneten ara model
- `App\Models\Admin\Users\UserPermission`: Kullanıcı izin ilişkilerini yöneten ara model

## Genel Bakış

Bu modül aşağıdaki temel işlevleri içerir:

- **Kullanıcı Kimlik Doğrulama**: Login, kayıt ve şifre sıfırlama
- **Profil Yönetimi**: Kullanıcı bilgilerini güncelleme
- **İki Faktörlü Kimlik Doğrulama**: Ekstra güvenlik katmanı
- **Rol Yönetimi**: Rollerin atanması ve yönetilmesi
- **İzin Yönetimi**: Belirli kaynaklara erişim kontrolü
- **API Entegrasyonu**: Laravel Sanctum ile güvenli API erişimi

## Kurulum Teknolojileri ve Paketler

- **Laravel Jetstream**: Temel kimlik doğrulama ve kullanıcı profil yönetimi
- **Livewire**: Reaktif bileşenler için kullanıldı
- **Vuexy Bootstrap Entegrasyonu**: Tailwind CSS yerine Bootstrap kullanımı için
- **Spatie Laravel-Permission**: Rol ve izin yönetimi

## Kurulum ve Entegrasyon Adımları

Modül kurulumu ve entegrasyonu aşağıdaki adımlarla tamamlanmıştır:

1. Laravel Jetstream kurulumu (Vite - Livewire ile)
2. Vuexy Bootstrap entegrasyonu (Tailwind CSS yerine)
3. Spatie Permission paketinin entegrasyonu
4. Veritabanı tablolarının oluşturulması
5. Menü ve rota yapılandırmaları
6. Controller oluşturulması
7. Vuexy'nin kullanıcı listesi, rol ve izin sayfalarının entegrasyonu

## Dosya Organizasyonu

Tüm kullanıcı yönetimi ile ilgili dosyalar, aşağıdaki klasör yapısı içinde organize edilmiştir:

- **Controller**: `App\Http\Controllers\Admin\Users\UserController`
- **Model**: 
  - `App\Models\Admin\Users\User`
  - `App\Models\Admin\Users\UserRole`
  - `App\Models\Admin\Users\UserPermission`
- **View Dosyaları**: `resources\views\content\admin\users\` klasörü altında
  - `user-list.blade.php` - Kullanıcı listeleme sayfası
  - `roles.blade.php` - Rol yönetimi sayfası
  - `permissions.blade.php` - İzin yönetimi sayfası

## Admin Paneli Menü Yapılandırması

Admin panelinde Settings menüsünün üzerine Users menüsü eklenmiştir:

```json
{
  "name": "Users",
  "icon": "menu-icon tf-icons ti ti-users",
  "slug": "admin.users",
  "url": "admin/users"
}
```

Bu menü elemanı `/admin/users` rotasına bağlanmıştır ve kullanıcı listesi sayfasını görüntülemektedir.

## Controller Yapısı

`App\Http\Controllers\Admin\Users\UserController` sınıfı oluşturularak, kullanıcı yönetimi işlemleri için gerekli metodlar eklenmiştir:

```php
public function index()
{
    $pageConfigs = [
        'pageHeader' => false,
        'contentLayout' => "default",
        'pageClass' => 'app-user-list',
    ];

    return view('content.admin.users.user-list', ['pageConfigs' => $pageConfigs]);
}
```

## Route Yapılandırması

Web rotalarını düzenlerken, README.md'de belirtildiği gibi sadece `/* ========== PATEEZ NEWS ROTALAR BAŞLANGIÇ ========== */` ve `/* ========== PATEEZ NEWS ROTALAR BİTİŞ ========== */` blokları arasına ekleme yapılmıştır:

```php
Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
```

## Sayfalar ve Özellikler

Vuexy temasının hazır bileşenleri kullanılarak aşağıdaki sayfalar entegre edilmiştir:

- **Kullanıcı Listesi**: `user-list.blade.php` - Kullanıcıların listelenmesi, filtrelenmesi ve yönetilmesi
- **Roller**: `roles.blade.php` - Rol oluşturma, düzenleme ve rol yetki atama işlemleri
- **İzinler**: `permissions.blade.php` - İzin oluşturma, düzenleme ve yönetimi

## Veri Tabloları

Tüm sayfalarda Vuexy'nin DataTable yapısı kullanılarak aşağıdaki özellikler sunulmaktadır:

- Excel, PDF, CSV dışa aktarma
- Yazdırma ve kopyalama
- Gelişmiş filtreleme ve sıralama
- AJAX tabanlı veri yükleme ve server-side işleme
- Responsive tasarım desteği

## Form Doğrulama

- FormValidation.io kütüphanesi entegrasyonu
- Hem client-side hem server-side doğrulama
- Özel doğrulama kuralları ve mesajları
- Anında geri bildirim

## Admin Paneli HTML + Laravel Yapısı

Proje gerekliliklerine uygun olarak, admin paneli tamamen Laravel + HTML (Vuexy Template + Vite) yapısı kullanılarak geliştirilmiştir. Vue.js sadece frontend uygulamasında kullanılacaktır.

## Önemli Klasör Yapısı Standardı

Bu projede, modüllerin daha düzenli ve organize olması için aşağıdaki klasör yapısı standardı benimsenmiştir:

- `app\Http\Controllers\Admin\[ModülAdı]\[Controller].php` - Controller dosyaları
- `app\Models\Admin\[ModülAdı]\` - Model dosyaları
- `resources\views\content\admin\[modülAdı]\` - İlgili modüle ait view dosyaları

Bu modüler yapı sayesinde projenin sürdürülebilirliği artırılmış ve farklı modüller arasında geçiş daha kolay hale getirilmiştir. Tüm kullanıcı yönetimi ile ilgili dosyalar ilgili klasörler altında toplanmıştır.

## Kullanım Senaryoları

1. **Kullanıcı Yönetimi**: Kullanıcı ekleme, düzenleme, silme ve pasif/aktif yapma
2. **Rol Atama**: Kullanıcılara rol atama ve yönetme
3. **İzin Yönetimi**: Hassas izinleri düzenleme ve rollere atama
4. **Kullanıcı Detayları**: Kullanıcı profillerini ve ayarlarını düzenleme

## Teknik Notlar

- Laravel Policy sistemi ile entegre çalışmaktadır
- Cache kullanımı optimize edilmiştir
- Rol ve izin tabanlı Middleware tanımlamaları yapılmıştır
- Vuexy'nin DataTable varyantlarının tümü desteklenmektedir

---

**Not**: Bu modülün detaylı kullanımı ve özelleştirme seçenekleri için Vuexy ve Laravel Jetstream dokümantasyonlarına başvurabilirsiniz.
