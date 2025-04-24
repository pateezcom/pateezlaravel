-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 24 Nis 2025, 13:46:40
-- Sunucu sürümü: 5.7.24
-- PHP Sürümü: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `pateezlaravel`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('translations.en', 'a:0:{}', 1745488803);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_rtl` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `icon`, `is_rtl`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 'flag-icon-us', 0, 1, 1, '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(2, 'Türkçe', 'tr', 'flag-icon-tr', 0, 0, 1, '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(3, 'العربية', 'ar', 'flag-icon-sa', 1, 0, 1, '2025-04-24 06:40:44', '2025-04-24 06:40:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_24_000001_create_languages_table', 2),
(5, '2025_04_24_000002_create_translations_table', 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Q0gLcXvtJiujIVQJf2x5rNKAAB734nJ4LVdxyLV0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXcwaG1DekVtaHFZa3ZiU1hNZmRJT0pCWXpKblY2ZXpnUVgxbWVjWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcHAvYWNjZXNzLXJvbGVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1745490993);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `translations`
--

CREATE TABLE `translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `translations`
--

INSERT INTO `translations` (`id`, `language_id`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'default', 'home', 'Home', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(2, 2, 'default', 'home', 'Ana Sayfa', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(3, 1, 'default', 'about', 'About', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(4, 2, 'default', 'about', 'Hakkımızda', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(5, 1, 'default', 'contact', 'Contact', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(6, 2, 'default', 'contact', 'İletişim', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(7, 1, 'default', 'settings', 'Settings', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(8, 2, 'default', 'settings', 'Ayarlar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(9, 1, 'default', 'language_settings', 'Language Settings', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(10, 2, 'default', 'language_settings', 'Dil Ayarları', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(11, 1, 'default', 'search', 'Search', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(12, 2, 'default', 'search', 'Ara', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(13, 1, 'default', 'save', 'Save', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(14, 2, 'default', 'save', 'Kaydet', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(15, 1, 'default', 'cancel', 'Cancel', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(16, 2, 'default', 'cancel', 'İptal', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(17, 1, 'default', 'delete', 'Delete', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(18, 2, 'default', 'delete', 'Sil', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(19, 1, 'default', 'edit', 'Edit', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(20, 2, 'default', 'edit', 'Düzenle', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(21, 1, 'default', 'add', 'Add', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(22, 2, 'default', 'add', 'Ekle', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(23, 1, 'default', 'submit', 'Submit', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(24, 2, 'default', 'submit', 'Gönder', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(25, 1, 'default', 'login', 'Login', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(26, 2, 'default', 'login', 'Giriş Yap', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(27, 1, 'default', 'logout', 'Logout', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(28, 2, 'default', 'logout', 'Çıkış Yap', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(29, 1, 'default', 'register', 'Register', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(30, 2, 'default', 'register', 'Kayıt Ol', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(31, 1, 'default', 'profile', 'Profile', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(32, 2, 'default', 'profile', 'Profil', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(33, 1, 'default', 'languages', 'Languages', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(34, 2, 'default', 'languages', 'Diller', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(35, 1, 'default', 'add_language', 'Add Language', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(36, 2, 'default', 'add_language', 'Dil Ekle', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(37, 1, 'default', 'edit_language', 'Edit Language', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(38, 2, 'default', 'edit_language', 'Dil Düzenle', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(39, 1, 'default', 'language_name', 'Language Name', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(40, 2, 'default', 'language_name', 'Dil Adı', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(41, 1, 'default', 'language_code', 'Language Code', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(42, 2, 'default', 'language_code', 'Dil Kodu', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(43, 1, 'default', 'default_language', 'Default Language', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(44, 2, 'default', 'default_language', 'Varsayılan Dil', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(45, 1, 'default', 'active', 'Active', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(46, 2, 'default', 'active', 'Aktif', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(47, 1, 'default', 'inactive', 'Inactive', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(48, 2, 'default', 'inactive', 'Pasif', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(49, 1, 'default', 'rtl', 'Right to Left', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(50, 2, 'default', 'rtl', 'Sağdan Sola', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(51, 1, 'default', 'translations', 'Translations', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(52, 2, 'default', 'translations', 'Çeviriler', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(53, 1, 'default', 'translation_key', 'Translation Key', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(54, 2, 'default', 'translation_key', 'Çeviri Anahtarı', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(55, 1, 'default', 'translation_value', 'Translation Value', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(56, 2, 'default', 'translation_value', 'Çeviri Değeri', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(57, 1, 'default', 'import_translations', 'Import Translations', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(58, 2, 'default', 'import_translations', 'Çevirileri İçe Aktar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(59, 1, 'default', 'export_translations', 'Export Translations', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(60, 2, 'default', 'export_translations', 'Çevirileri Dışa Aktar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(61, 1, 'default', 'set_as_default', 'Set as Default', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(62, 2, 'default', 'set_as_default', 'Varsayılan Olarak Ayarla', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(63, 1, 'admin', 'dashboard', 'Dashboard', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(64, 2, 'admin', 'dashboard', 'Gösterge Paneli', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(65, 1, 'admin', 'users', 'Users', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(66, 2, 'admin', 'users', 'Kullanıcılar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(67, 1, 'admin', 'roles', 'Roles', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(68, 2, 'admin', 'roles', 'Roller', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(69, 1, 'admin', 'permissions', 'Permissions', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(70, 2, 'admin', 'permissions', 'İzinler', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(71, 1, 'admin', 'admin_panel', 'Admin Panel', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(72, 2, 'admin', 'admin_panel', 'Yönetim Paneli', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(73, 1, 'admin', 'manage_languages', 'Manage Languages', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(74, 2, 'admin', 'manage_languages', 'Dilleri Yönet', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(75, 1, 'frontend', 'latest_news', 'Latest News', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(76, 2, 'frontend', 'latest_news', 'Son Haberler', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(77, 1, 'frontend', 'popular_posts', 'Popular Posts', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(78, 2, 'frontend', 'popular_posts', 'Popüler Yazılar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(79, 1, 'frontend', 'categories', 'Categories', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(80, 2, 'frontend', 'categories', 'Kategoriler', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(81, 1, 'frontend', 'tags', 'Tags', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(82, 2, 'frontend', 'tags', 'Etiketler', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(83, 1, 'frontend', 'read_more', 'Read More', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(84, 2, 'frontend', 'read_more', 'Devamını Oku', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(85, 1, 'frontend', 'share', 'Share', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(86, 2, 'frontend', 'share', 'Paylaş', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(87, 1, 'frontend', 'comments', 'Comments', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(88, 2, 'frontend', 'comments', 'Yorumlar', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(89, 1, 'frontend', 'subscribe', 'Subscribe', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(90, 2, 'frontend', 'subscribe', 'Abone Ol', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(91, 1, 'frontend', 'newsletter', 'Newsletter', '2025-04-24 06:40:44', '2025-04-24 06:40:44'),
(92, 2, 'frontend', 'newsletter', 'Bülten', '2025-04-24 06:40:44', '2025-04-24 06:40:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mr. Justen Dietrich II', 'nicolette47@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'h4gUxOHrh5', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(2, 'Shanon Corwin', 'elyssa.spencer@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', '5LymwyMlS0', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(3, 'Luis Oberbrunner', 'camren65@example.com', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'Y5A2C6oh4T', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(4, 'Gerald Kshlerin', 'aurelia.goldner@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'GTzsGod8vP', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(5, 'Aleen Kuhn', 'green.celestine@example.com', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', '7Q2yEAK2dU', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(6, 'Prof. Darrick Walter', 'nhamill@example.net', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'Zguebsp3Bc', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(7, 'Prof. Constance Cummerata IV', 'boyer.pietro@example.net', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'aRLbv9vyIT', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(8, 'Bette Hand', 'vida.macejkovic@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'FivZCvVjeX', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(9, 'Addison Runolfsdottir', 'konopelski.jackeline@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'YycSi0r5VF', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(10, 'Gwen Lesch', 'eliezer.feest@example.org', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', 'k7zEN52d9g', '2025-04-24 03:43:57', '2025-04-24 03:43:57'),
(11, 'Test User', 'test@example.com', '2025-04-24 03:43:57', '$2y$12$.yZFz7pCEze7iN3hixHnz.h7hYWaFs4.fsfHutHrsy4iTRn034MUG', '4NtRjRKQrQ', '2025-04-24 03:43:57', '2025-04-24 03:43:57');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Tablo için indeksler `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Tablo için indeksler `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translations_language_id_group_key_index` (`language_id`,`group`,`key`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
