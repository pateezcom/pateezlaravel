# Pateez Haber Projesi

Bu proje, Vuexy teması kullanarak Laravel backend ve Vue.js frontend ile geliştirilen modern bir haber ve buzz scripti uygulamasıdır.

> **ÖNEMLİ NOT**: Bu README dosyası projenin genel çatısını ve önemli bilgileri içerir. Tamamlanan modüllerin detaylı dokümantasyonu, ilgili modülün kendi README dosyasında yer almaktadır. Yeni bir modül hakkında bilgi edinmek için, sadece o modülün README dosyasını incelemeniz yeterlidir, her oturumda tüm modülleri incelemenize gerek yoktur.

## Proje Yapısı ve Mimari

Proje üç katmanlı bir mimariye sahiptir:

1. **Admin Panel**: Laravel + HTML (Vuexy Template + Vite) - Admin işlemleri için kullanılır
2. **Frontend Website**: Vue.js - Kullanıcıların göreceği ön yüz
3. **Mobil Uygulama**: Flutter - iOS ve Android için native uygulama (Henüz başlanmadı)

> **ÖNEMLİ NOT**: Vue.js **SADECE** ön yüzde kullanılacaktır. Admin panelinde Vue.js kullanılmayacak, Laravel + HTML yapısı korunacaktır.

## Kullanılan Teknolojiler

- **Backend**: PHP 8.x, Laravel Framework
- **Frontend**: Vue.js 3.x, Vue Router, Bootstrap 5
- **Admin Panel**: Laravel Blade, HTML, CSS, JavaScript
- **Build Tool**: Vite
- **Tema**: Vuexy Admin Template (Laravel + HTML Vite Sürümü)

## Klasör Yapısı

- `resources/js/frontend/` → Vue.js frontend uygulaması
- `resources/views/content/` → Laravel Blade şablonları (Admin Panel)
- `app/Http/Controllers/` → Laravel kontrolcüleri
- `resources/js/admin/` → Admin panel JavaScript dosyaları

## Önemli Notlar

- Her modül önce admin panelde geliştirildikten sonra frontend Vue.js ile entegre edilecektir
- Admin panelde kesinlikle HTML + Laravel yapısı korunacaktır
- Tüm çeviriler için Laravel'in yerleşik `__()` fonksiyonu kullanılmalıdır
- Rota tanımlamalarını yaparken sadece `web.php` dosyasındaki `/* ========== PATEEZ NEWS ROTALAR BAŞLANGIÇ ========== */` ve `/* ========== PATEEZ NEWS ROTALAR BİTİŞ ========== */` blokları arasına ekleme yapılacaktır

## Geliştirme Süreci

1. Admin panel modülü geliştirme ("admini yap" talimatı ile)
2. Frontend Vue.js entegrasyonu ("ön paneli yap" talimatı ile)
3. Mobil uygulama entegrasyonu ("flutter'ı yap" talimatı ile)

> **ÖNEMLİ NOT**: Her aşamada bir sonraki adıma geçmek için açık talimat beklenecektir. Açık bir talimat olmadan bir sonraki aşamaya geçilmeyecektir.

## Kod Standartları

- **Kod Açıklamaları**: Tüm fonksiyonlar ve kod blokları için önce İngilizce, sonra Türkçe olarak açıklama yazılacaktır
- **Değişken İsimleri**: Anlaşılır ve tutarlı isimlendirme yapılacaktır
- **Girintileme**: Laravel ve Vue.js standartlarına uygun olacaktır

## Form Doğrulama Standartları

- FormValidation.io kütüphanesi kullanılacaktır
- Form doğrulama kuralları ayrı bir JavaScript dosyasında tanımlanacaktır
- Client-side ve server-side doğrulama birlikte kullanılacaktır

## Vuexy Tablo Standartları

- DataTable sınıflandırması ve DOM yapısı Vuexy standartlarında olacaktır
- Tüm tablolarda Excel, PDF, CSV, yazdırma ve kopyalama düğmeleri bulunacaktır
- Controller'lar hem normal sayfa yüklenişini hem de AJAX isteklerini destekleyecektir

## Tamamlanan Modüller ve Paketler

- [Dil Paketi Modülü](C:\Users\nasipse\Desktop\pateezlaravel\dilpaketireadme.md) (2025-04-28)
  
  Laravel ve Vuexy teması ile tam entegre edilmiş çok dilli web sitesi desteği. Veritabanı tabanlı çeviri sistemi, dil değiştirme, çeviri yönetimi ve SEO dostu URL yapısını içerir. Laravel'in __() fonksiyonu ile entegre çalışır.

## Yeni Sohbet Başlatma

Yeni bir sohbet başlatıldığında, bu README.md dosyasını ve GitHub repo linkini paylaşmanız yeterli olacaktır. Herhangi bir ek açıklamaya gerek kalmadan doğrudan "Projeni öğrendim, nasıl devam etmek istersin?" mesajıyla devam edilecektir.