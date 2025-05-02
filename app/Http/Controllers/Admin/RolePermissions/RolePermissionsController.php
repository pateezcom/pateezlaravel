<?php

namespace App\Http\Controllers\Admin\RolePermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RolePermissionsController extends Controller
{
  /**
   * Display the roles and permissions page
   * Rol ve izinler sayfasını gösterir
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    // Tüm rolleri getir ve Admin rolünü en başa al
    $roles = Role::with('permissions')->get();
    
    // Admin rolünü en başa yerleştir
    $adminRole = $roles->filter(function($role) {
      return in_array(strtolower($role->name), ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin']);
    })->first();
    
    if ($adminRole) {
      // Admin rolünü koleksiyondan çıkar
      $otherRoles = $roles->filter(function($role) use ($adminRole) {
        return $role->id !== $adminRole->id;
      });
      
      // Yeni koleksiyon oluştur: önce admin, sonra diğer roller
      $roles = collect([$adminRole])->merge($otherRoles);
    }
    
    // Tüm izinleri getir
    $permissions = Permission::all();
    
    // Menü yapısını kullanarak izin kategorilerini oluştur
    $menuJson = File::get(resource_path('menu/verticalMenu.json'));
    $menuData = json_decode($menuJson, true);
    $permissionCategories = $this->buildPermissionCategoriesFromMenu($menuData);
    
    // Toplam kullanıcı sayısını getir
    $users = User::with('roles')->get();
    
    // Her rol için kullanıcı sayısını hesapla
    $roleUserCounts = [];
    foreach ($roles as $role) {
      $roleUserCounts[$role->name] = $users->filter(function ($user) use ($role) {
        return $user->hasRole($role->name);
      })->count();
    }
    
    return view('content.admin.rolepermissions.app-access-roles', [
      'roles' => $roles,
      'permissions' => $permissions,
      'permissionCategories' => $permissionCategories,
      'roleUserCounts' => $roleUserCounts,
      'users' => $users
    ]);
  }
  
  /**
   * Create a new role with permissions
   * Yeni bir rol ve izinlerini oluşturur
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeRole(Request $request)
  {
    try {
      // Debug bilgileri
      Log::info('Store role request:', [
        'request_data' => $request->all()
      ]);
      
      // Form verilerini doğrula
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'nullable|array'
      ]);
      
      if ($validator->fails()) {
        Log::warning('Validation failed on role creation:', ['errors' => $validator->errors()->toArray()]);
        return response()->json([
          'success' => false,
          'message' => __('form_validation_error'),
          'errors' => $validator->errors()
        ], 422);
      }
      
      // Rol oluştur
      $role = Role::create([
        'name' => $request->name,
        'guard_name' => 'web'
      ]);
      
      Log::info('New role created:', ['role_id' => $role->id, 'role_name' => $role->name]);
      
      // İzinleri rol ile ilişkilendir
      if ($request->has('permissions') && is_array($request->permissions)) {
        $role->syncPermissions($request->permissions);
        Log::info('Permissions assigned to new role:', ['permissions' => $request->permissions]);
      }
      
      return response()->json([
        'success' => true,
        'message' => __('role_created_successfully'),
        'role' => $role
      ]);
    } catch (\Exception $e) {
      Log::error('Role creation error: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString()
      ]);
      return response()->json([
        'success' => false,
        'message' => __('role_creation_error') . ': ' . $e->getMessage()
      ], 500);
    }
  }
  
  /**
   * Update a role and its permissions
   * Bir rolü ve izinlerini günceller
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateRole(Request $request, $id)
  {
    try {
      // Debug bilgileri
      Log::info('Update role request:', [
        'id' => $id,
        'request_data' => $request->all()
      ]);
      
      // Rolü bul
      $role = Role::findOrFail($id);
      Log::info('Found role:', ['role' => $role->toArray()]);
      
      // Admin rolünü kontrol et
      $protectedRoles = ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin'];
      if (in_array(strtolower($role->name), $protectedRoles) && $request->name !== $role->name) {
        Log::warning('Attempt to rename protected role', ['role' => $role->name, 'new_name' => $request->name]);
        return response()->json([
          'success' => false,
          'message' => __('cannot_rename_admin_role')
        ], 403);
      }
      
      // Form verilerini doğrula
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name,' . $id,
        'permissions' => 'nullable|array'
      ]);
      
      if ($validator->fails()) {
        Log::warning('Validation failed:', ['errors' => $validator->errors()->toArray()]);
        return response()->json([
          'success' => false,
          'message' => __('form_validation_error'),
          'errors' => $validator->errors()
        ], 422);
      }
      
      // Rolü güncelle
      $role->name = $request->name;
      $role->save();
      Log::info('Role updated', ['role_id' => $role->id, 'new_name' => $role->name]);
      
      // İzinleri güncelle
      if ($request->has('permissions')) {
        $role->syncPermissions($request->permissions);
        Log::info('Permissions synced', ['permissions' => $request->permissions]);
      } else {
        // Eğer permissions parametresi yoksa, tüm izinleri kaldır
        $role->syncPermissions([]);
        Log::info('All permissions removed');
      }
      
      return response()->json([
        'success' => true,
        'message' => __('role_updated_successfully'),
        'role' => $role
      ]);
    } catch (\Exception $e) {
      Log::error('Role update error: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString()
      ]);
      return response()->json([
        'success' => false,
        'message' => __('role_update_error') . ': ' . $e->getMessage()
      ], 500);
    }
  }
  
  /**
   * Delete a role
   * Bir rolü siler
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function deleteRole($id)
  {
    try {
      // Rolü bul
      $role = Role::findOrFail($id);
      
      // Admin rolünü silmeye çalışıyorsa engelle
      $protectedRoles = ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin'];
      if (in_array(strtolower($role->name), $protectedRoles)) {
        return response()->json([
          'success' => false,
          'message' => __('cannot_delete_admin_role')
        ], 403);
      }
      
      // Rolü sil
      $role->delete();
      
      return response()->json([
        'success' => true,
        'message' => __('role_deleted_successfully')
      ]);
    } catch (\Exception $e) {
      Log::error('Role deletion error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => __('role_deletion_error')
      ], 500);
    }
  }
  
  /**
   * Get users with specific role
   * Belirli bir role sahip kullanıcıları getirir
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function getRoleUsers($id)
  {
    try {
      // Rolü bul
      $role = Role::findOrFail($id);
      
      // Bu role sahip kullanıcıları getir
      $users = User::role($role->name)->get();
      
      return response()->json([
        'success' => true,
        'users' => $users
      ]);
    } catch (\Exception $e) {
      Log::error('Get role users error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => __('get_role_users_error')
      ], 500);
    }
  }
  
  /**
   * Get all permissions
   * Tüm izinleri getirir
   *
   * @return \Illuminate\Http\Response
   */
  public function getPermissions()
  {
    try {
      $permissions = Permission::all();
      
      return response()->json([
        'success' => true,
        'permissions' => $permissions
      ]);
    } catch (\Exception $e) {
      Log::error('Get permissions error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => __('get_permissions_error')
      ], 500);
    }
  }
  
  /**
   * Build permission categories from menu structure
   * Menü yapısından izin kategorilerini oluşturur
   *
   * @param  array  $menuData
   * @return array
   */
  private function buildPermissionCategoriesFromMenu($menuData)
  {
    $categories = [];
    
    // Ana menü öğelerini döngüyle işle
    foreach ($menuData['menu'] as $menuItem) {
      // Menü başlığını atla
      if (isset($menuItem['menuHeader'])) {
        continue;
      }
      
      // Ana menü öğesini kategori olarak ekle
      $categoryName = $menuItem['name'];
      $categorySlug = isset($menuItem['slug']) ? $menuItem['slug'] : Str::slug($categoryName);
      $categoryIcon = isset($menuItem['icon']) ? $menuItem['icon'] : 'menu-icon tf-icons ti ti-box';
      
      $category = [
        'name' => $categoryName,
        'slug' => $categorySlug,
        'icon' => $categoryIcon,
        'permissions' => []
      ];
      
      // Her kategori için 'okuma' ve 'tam yetki' izinlerini ekle
      $category['permissions'][] = [
        'id' => $categorySlug . '.read',
        'name' => __('read_permission'),
        'key' => 'read'
      ];
      
      $category['permissions'][] = [
        'id' => $categorySlug . '.full',
        'name' => __('full_permission'),
        'key' => 'full'
      ];
      
      // Alt menü öğeleri varsa işle
      if (isset($menuItem['submenu']) && is_array($menuItem['submenu'])) {
        $subCategories = [];
        
        foreach ($menuItem['submenu'] as $submenuItem) {
          $subCategoryName = $submenuItem['name'];
          $subCategorySlug = isset($submenuItem['slug']) ? $submenuItem['slug'] : Str::slug($subCategoryName);
          
          $subCategory = [
            'name' => $subCategoryName,
            'slug' => $subCategorySlug,
            'permissions' => [
              [
                'id' => $subCategorySlug . '.read',
                'name' => __('read_permission'),
                'key' => 'read'
              ],
              [
                'id' => $subCategorySlug . '.full',
                'name' => __('full_permission'),
                'key' => 'full'
              ]
            ]
          ];
          
          $subCategories[] = $subCategory;
        }
        
        if (!empty($subCategories)) {
          $category['subCategories'] = $subCategories;
        }
      }
      
      $categories[] = $category;
    }
    
    return $categories;
  }
  
  /**
   * Sync database permissions with menu structure
   * Menü yapısındaki izinleri veritabanı ile senkronize eder
   *
   * @return \Illuminate\Http\Response
   */
  public function syncPermissions()
  {
    try {
      // Menü yapısını oku
      $menuJson = File::get(resource_path('menu/verticalMenu.json'));
      $menuData = json_decode($menuJson, true);
      
      // Kategorileri oluştur
      $categories = $this->buildPermissionCategoriesFromMenu($menuData);
      
      // Yeni izinleri oluştur ve mevcut izinleri sakla
      $permissionsToKeep = [];
      $createdCount = 0;
      
      foreach ($categories as $category) {
        foreach ($category['permissions'] as $permission) {
          $permissionName = $permission['id'];
          $permissionsToKeep[] = $permissionName;
          
          // İzin yoksa oluştur
          $created = Permission::firstOrCreate(
            ['name' => $permissionName, 'guard_name' => 'web']
          );
          
          if ($created->wasRecentlyCreated) {
            $createdCount++;
          }
        }
        
        // Alt kategorileri işle
        if (isset($category['subCategories'])) {
          foreach ($category['subCategories'] as $subCategory) {
            foreach ($subCategory['permissions'] as $permission) {
              $permissionName = $permission['id'];
              $permissionsToKeep[] = $permissionName;
              
              // İzin yoksa oluştur
              $created = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
              );
              
              if ($created->wasRecentlyCreated) {
                $createdCount++;
              }
            }
          }
        }
      }
      
      // Toplam işlem sayısını döndür
      return response()->json([
        'success' => true,
        'message' => __('permissions_synced_successfully'),
        'created' => $createdCount,
        'total' => count($permissionsToKeep)
      ]);
    } catch (\Exception $e) {
      Log::error('Sync permissions error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => __('sync_permissions_error')
      ], 500);
    }
  }
}