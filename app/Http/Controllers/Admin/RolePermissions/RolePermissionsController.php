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

class RolePermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $adminRole = $roles->filter(function($role) {
            return in_array(strtolower($role->name), ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin']);
        })->first();

        if ($adminRole) {
            $otherRoles = $roles->filter(function($role) use ($adminRole) {
                return $role->id !== $adminRole->id;
            });
            $roles = collect([$adminRole])->merge($otherRoles);
        }

        $permissions = Permission::all();
        $menuJson = File::get(resource_path('menu/verticalMenu.json'));
        $menuData = json_decode($menuJson, true);
        $permissionCategories = $this->buildPermissionCategoriesFromMenu($menuData);
        $users = User::with('roles')->get();
        $roleUserCounts = [];
        foreach ($roles as $role) {
            $roleUserCounts[$role->name] = $users->filter(function ($user) use ($role) {
                return $user->hasRole($role->name);
            })->count();
        }

        return view('content.admin.rolepermissions.rolepermissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissionCategories' => $permissionCategories,
            'roleUserCounts' => $roleUserCounts,
            'users' => $users
        ]);
    }

    public function storeRole(Request $request)
    {
        // İzinleri önceden oluştur
        $this->syncPermissions();

        $data = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,name'
        ], [
            'name.required' => __('role_name_required'),
            'name.min' => __('role_name_min_length'),
            'name.unique' => __('role_name_unique'),
            'permissions.*.exists' => __('permission_not_found')
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $permissions = $request->input('permissions', []);

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('role_created_successfully'),
                'role' => $role->load('permissions')
            ]);
        }

        return redirect()->back()->with('success', __('role_created_successfully'));
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,name'
        ], [
            'name.required' => __('role_name_required'),
            'name.min' => __('role_name_min_length'),
            'name.unique' => __('role_name_unique'),
            'permissions.*.exists' => __('permission_not_found')
        ]);

        $role->update(['name' => $data['name']]);
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('role_updated_successfully'),
                'role' => $role->load('permissions')
            ]);
        }

        return redirect()->back()->with('success', __('role_updated_successfully'));
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            $protectedRoles = ['admin', 'administrator', 'yönetici', 'super admin', 'superadmin'];
            if (in_array(strtolower($role->name), $protectedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => __('cannot_delete_admin_role')
                ], 403);
            }

            DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
            DB::table('model_has_roles')->where('role_id', $role->id)->delete();
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => __('role_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('role_deletion_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRoleUsers($id)
    {
        try {
            $role = Role::findOrFail($id);
            $users = User::role($role->name)->get();
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('get_role_users_error')
            ], 500);
        }
    }

    public function getPermissions()
    {
        try {
            $permissions = Permission::all();
            return response()->json([
                'success' => true,
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('get_permissions_error')
            ], 500);
        }
    }

    private function buildPermissionCategoriesFromMenu($menuData)
    {
        $categories = [];
        foreach ($menuData['menu'] as $menuItem) {
            if (isset($menuItem['menuHeader'])) {
                continue;
            }

            $categoryName = $menuItem['name'];
            $categorySlug = isset($menuItem['slug']) ? $menuItem['slug'] : Str::slug($categoryName);
            $categoryIcon = isset($menuItem['icon']) ? $menuItem['icon'] : 'menu-icon tf-icons ti ti-box';

            $category = [
                'name' => $categoryName,
                'slug' => $categorySlug,
                'icon' => $categoryIcon,
                'permissions' => []
            ];

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

    public function syncPermissions()
    {
        try {
            $menuJson = File::get(resource_path('menu/verticalMenu.json'));
            $menuData = json_decode($menuJson, true);
            $categories = $this->buildPermissionCategoriesFromMenu($menuData);

            $permissionsToKeep = [];
            $createdCount = 0;

            foreach ($categories as $category) {
                foreach ($category['permissions'] as $permission) {
                    $permissionName = $permission['id'];
                    $permissionsToKeep[] = $permissionName;
                    $created = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                    if ($created->wasRecentlyCreated) {
                        $createdCount++;
                    }
                }
                if (isset($category['subCategories'])) {
                    foreach ($category['subCategories'] as $subCategory) {
                        foreach ($subCategory['permissions'] as $permission) {
                            $permissionName = $permission['id'];
                            $permissionsToKeep[] = $permissionName;
                            $created = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                            if ($created->wasRecentlyCreated) {
                                $createdCount++;
                            }
                        }
                    }
                }
            }

            // Admin kullanıcısına tüm izinleri atayın
            $admin = User::find(1);
            if ($admin && $admin->hasRole('admin')) {
                $admin->syncPermissions($permissionsToKeep);
            }

            return response()->json([
                'success' => true,
                'message' => __('permissions_synced_successfully'),
                'created' => $createdCount,
                'total' => count($permissionsToKeep)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('sync_permissions_error')
            ], 500);
        }
    }
}
