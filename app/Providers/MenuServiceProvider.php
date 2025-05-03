<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $verticalJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
        $verticalMenu = json_decode($verticalJson);
        $horizontalJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
        $horizontalMenu = json_decode($horizontalJson);

        // Log JSON parsing for debugging
        Log::info('Vertical Menu JSON', ['menu' => $verticalJson]);

        View::composer('*', function ($view) use ($verticalMenu, $horizontalMenu) {
            if (Auth::check()) {
                $user = Auth::user();
                $filteredVertical = $this->filterMenuByPermissions($verticalMenu, $user);
                $filteredHorizontal = $this->filterMenuByPermissions($horizontalMenu, $user);

                // Log filtered menu
                Log::info('Filtered Vertical Menu', ['menu' => json_encode($filteredVertical)]);

                $view->with('menuData', [$filteredVertical, $horizontalMenu]);
            } else {
                $view->with('menuData', [$verticalMenu, $horizontalMenu]);
            }
        });
    }

    /**
     * Filter menu items based on user permissions.
     * Admin role bypasses all permission checks.
     */
    private function filterMenuByPermissions($menuObj, $user)
    {
        // Log user roles and permissions
        Log::info('User Permissions Check', [
            'user_id' => $user->id,
            'roles' => $user->roles->pluck('name')->toArray(),
            'permissions' => $user->permissions->pluck('name')->toArray()
        ]);

        // Check admin role
        $hasAdminRole = $user->hasAnyRole(['Super Admin', 'admin']);
        Log::info('Admin Role Check', ['hasAdminRole' => $hasAdminRole]);

        if ($hasAdminRole) {
            Log::info('Admin role detected, bypassing permission checks');
            return $menuObj;
        }

        if (!isset($menuObj->menu) || !is_array($menuObj->menu)) {
            Log::warning('Menu object is invalid', ['menuObj' => $menuObj]);
            return $menuObj;
        }

        $menuObj->menu = array_values(array_filter($menuObj->menu, function ($item) use ($user) {
            // Always show menu headers
            if (isset($item->menuHeader)) {
                return true;
            }

            // Check item permission
            $itemHasPermission = true;
            if (isset($item->permission) && $item->permission !== null) {
                $permissions = is_array($item->permission) ? $item->permission : explode('|', $item->permission);
                $itemHasPermission = $user->hasAnyPermission($permissions);
                Log::info('Checking permission for menu item', [
                    'item' => $item->name ?? 'unknown',
                    'permissions' => $permissions,
                    'hasPermission' => $itemHasPermission
                ]);
            }

            // Handle submenu
            if (isset($item->submenu) && is_array($item->submenu)) {
                $item->submenu = array_values(array_filter($item->submenu, function ($sub) use ($user) {
                    if (isset($sub->permission) && $sub->permission !== null) {
                        $subPermissions = is_array($sub->permission) ? $sub->permission : explode('|', $sub->permission);
                        $hasSubPermission = $user->hasAnyPermission($subPermissions);
                        Log::info('Checking permission for submenu item', [
                            'subitem' => $sub->name ?? 'unknown',
                            'permissions' => $subPermissions,
                            'hasPermission' => $hasSubPermission
                        ]);
                        return $hasSubPermission;
                    }
                    return true;
                }));

                return $itemHasPermission || count($item->submenu) > 0;
            }

            return $itemHasPermission;
        }));

        return $menuObj;
    }
}