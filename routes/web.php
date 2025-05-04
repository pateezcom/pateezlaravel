<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\FrontendController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;

// Admin Auth Controllers
use App\Http\Controllers\Admin\Auth\LoginController;

// Admin Settings Controllers
use App\Http\Controllers\Admin\Settings\Language\LanguageController;
use App\Http\Controllers\Admin\Settings\Language\LanguageSwitchController;
use App\Http\Controllers\Admin\Settings\Language\TranslationController;
use App\Http\Controllers\Admin\Settings\Language\JsTranslationController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Users\UserProfileController;
use App\Http\Controllers\Admin\RolePermissions\RolePermissionsController;

// Translation System Routes - Public erişim (JavaScript için gerekli)
Route::get('/lang/{locale}', [LanguageSwitchController::class, 'switchLang'])->name('lang.switch');
Route::get('/translations/refresh-cache', [JsTranslationController::class, 'refreshCache'])->name('translations.refresh-cache');
Route::get('/translations/js', [JsTranslationController::class, 'getTranslationsForJs'])->name('translations.js');

/* ========== PATEEZ NEWS ROTALAR BAŞLANGIÇ ========== */
// Admin Auth Routes - Giriş yapmadan erişilebilen rotalar
Route::get('/admin/login', [LoginController::class, 'index'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Admin Routes Group - Giriş yapmak zorunlu
Route::middleware(['auth'])->group(function () {
  // Admin Dashboard - Herkes için erişilebilir
  Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

  // Admin ana yönlendirme
  Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
  });

  // Users Routes
  Route::middleware(['permission:admin.users.read|admin.users.full'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/roles', [UserController::class, 'getRoles'])->name('admin.roles');
    Route::get('/admin/users/check-username', [UserController::class, 'checkUsername'])->name('admin.users.check-username');
    Route::get('/admin/users/check-email', [UserController::class, 'checkEmail'])->name('admin.users.check-email');
    Route::get('/admin/users/check-slug', [UserController::class, 'checkSlug'])->name('admin.users.check-slug');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::get('/admin/users/{id}/permissions/edit', [UserController::class, 'editPermissions'])->name('admin.users.edit-permissions');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('admin.users.update-permissions');
    Route::post('/admin/users/{id}/verify-email', [UserController::class, 'verifyEmail'])->name('admin.users.verify-email');
    Route::post('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::post('/admin/users/{id}/toggle-reward', [UserController::class, 'toggleReward'])->name('admin.users.toggle-reward');
    Route::post('/admin/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('admin.users.bulk-delete');
  });

  // User Profile Management - Kendi profili olduğu için herkes erişebilir
  Route::get('/admin/users/{id}/profile', [UserProfileController::class, 'index'])->name('admin.users.profile');
  Route::get('/admin/users/{id}/profile/security', [UserProfileController::class, 'security'])->name('admin.users.profile.security');
  Route::put('/admin/users/{id}/profile/account', [UserProfileController::class, 'updateAccount'])->name('admin.users.profile.update-account');
  Route::put('/admin/users/{id}/profile/password', [UserProfileController::class, 'updatePassword'])->name('admin.users.profile.update-password');
  Route::put('/admin/users/{id}/profile/photo', [UserProfileController::class, 'updateProfilePhoto'])->name('admin.users.profile.update-photo');
  Route::delete('/admin/users/{id}/profile/photo', [UserProfileController::class, 'deleteProfilePhoto'])->name('admin.users.profile.delete-photo');

  // Role & Permissions
  Route::middleware(['permission:admin.role.permissions.read|admin.role.permissions.full'])->group(function () {
    Route::get('/admin/role-permissions', [RolePermissionsController::class, 'index'])->name('admin.role.permissions');
    Route::post('/admin/role-permissions/store', [RolePermissionsController::class, 'storeRole'])->name('admin.role.permissions.store');
    Route::put('/admin/role-permissions/{id}', [RolePermissionsController::class, 'updateRole'])->name('admin.role.permissions.update');
    Route::delete('/admin/role-permissions/{id}', [RolePermissionsController::class, 'deleteRole'])->name('admin.role.permissions.delete');
    Route::get('/admin/role-permissions/{id}/users', [RolePermissionsController::class, 'getRoleUsers'])->name('admin.role.permissions.users');
    Route::post('/admin/role-permissions/sync', [RolePermissionsController::class, 'syncPermissions'])->name('admin.role.permissions.sync');
    Route::get('/admin/role-permissions/permissions', [RolePermissionsController::class, 'getPermissions'])->name('admin.role.permissions.list');
  });

  // Language Settings
  Route::middleware(['permission:admin.settings.languages.read|admin.settings.languages.full'])->group(function () {
    Route::get('/admin/settings/languages', [LanguageController::class, 'index'])->name('admin.settings.languages');
    Route::post('/admin/settings/languages', [LanguageController::class, 'store'])->name('admin.settings.languages.store');
    Route::post('/admin/settings/languages/check-unique', [LanguageController::class, 'checkUnique'])->name('admin.settings.languages.check-unique');
    Route::get('/admin/settings/languages/{id}/edit', [LanguageController::class, 'edit'])->name('admin.settings.languages.edit');
    Route::put('/admin/settings/languages/{id}', [LanguageController::class, 'update'])->name('admin.settings.languages.update');
    Route::delete('/admin/settings/languages/{id}', [LanguageController::class, 'destroy'])->name('admin.settings.languages.destroy');
    Route::post('/admin/settings/languages/{id}/set-default', [LanguageController::class, 'setDefault'])->name('admin.settings.languages.set-default');
    Route::post('/admin/settings/languages/import', [LanguageController::class, 'import'])->name('admin.settings.languages.import');
    Route::get('/admin/settings/languages/{id}/export', [LanguageController::class, 'export'])->name('admin.settings.languages.export');

    // Translations Routes - Çeviri yönetimi için route'lar
    Route::get('/admin/settings/translations/{id}', [TranslationController::class, 'edit'])->name('admin.settings.translations.edit');
    Route::put('/admin/settings/translations/{id}', [TranslationController::class, 'update'])->name('admin.settings.translations.update');
    Route::get('/admin/settings/translations/{id}/search', [TranslationController::class, 'search'])->name('admin.settings.translations.search');
    Route::post('/admin/settings/translations/{id}/add', [TranslationController::class, 'addTranslation'])->name('admin.settings.translations.add');
    Route::delete('/admin/settings/translations/{id}/delete/{translationId}', [TranslationController::class, 'deleteTranslation'])->name('admin.settings.translations.delete');
  });


});
/* ========== PATEEZ NEWS ROTALAR BİTİŞ ========== */

// Frontend Routes
Route::get('/home', [FrontendController::class, 'index'])->name('frontend.home');

// Main Page Route - Redirect to frontend
Route::get('/', function() {
  return redirect()->route('frontend.home');
});
