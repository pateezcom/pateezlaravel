<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->composeVerticalMenu();
    }

    protected function composeVerticalMenu()
    {
        view()->composer('layouts.sections.menu.verticalMenu', function ($view) {
            $user = Auth::user();
            $menu = $this->getVerticalMenu();

            if ($user && $user->hasRole('admin')) {
                // Log çağrısını kaldırdık
            } else {
                $menu['menu'] = array_filter($menu['menu'], function ($item) use ($user) {
                    if (isset($item['menuHeader'])) {
                        return true;
                    }
                    if (!isset($item['permission']) || ($user && $user->hasAnyPermission(explode('|', $item['permission'])))) {
                        if (isset($item['submenu'])) {
                            $item['submenu'] = array_filter($item['submenu'], function ($subItem) use ($user) {
                                return !isset($subItem['permission']) || ($user && $user->hasAnyPermission(explode('|', $subItem['permission'])));
                            });
                        }
                        return true;
                    }
                    return false;
                });
            }

            // Log çağrısını kaldırdık
            $view->with('menu', $menu);
        });
    }

    protected function getVerticalMenu()
    {
        // Menu items array
        // Menü öğeleri dizisi
        $menu = [
            'menu' => [
                [
                    'name' => 'dashboard',
                    'icon' => 'menu-icon tf-icons ti ti-dashboard',
                    'slug' => 'admin.dashboard',
                    'url' => 'admin/dashboard',
                    'permission' => null
                ],
                [
                    'name' => 'users',
                    'icon' => 'menu-icon tf-icons ti ti-users',
                    'slug' => 'admin.users',
                    'url' => 'admin/users',
                    'permission' => 'admin.users.read|admin.users.full'
                ],
                [
                    'name' => 'roles_permissions',
                    'icon' => 'menu-icon tf-icons ti ti-key',
                    'slug' => 'admin.role.permissions',
                    'url' => 'admin/role-permissions',
                    'permission' => 'admin.role.permissions.read|admin.role.permissions.full'
                ],
                [
                    'menuHeader' => 'settings'
                ],
                [
                    'name' => 'settings',
                    'icon' => 'menu-icon tf-icons ti ti-settings',
                    'slug' => 'admin.settings',
                    'permission' => 'admin.settings.read|admin.settings.full',
                    'submenu' => [
                        [
                            'url' => 'admin/settings/languages',
                            'name' => 'language_settings',
                            'slug' => 'admin.settings.languages',
                            'permission' => 'admin.settings.languages.read|admin.settings.languages.full'
                        ],
                        [
                            'url' => 'admin/settings/translations/1',
                            'name' => 'translation_settings',
                            'slug' => 'admin.settings.translations',
                            'permission' => 'admin.settings.translations.read|admin.settings.translations.full'
                        ]
                    ]
                ]
            ]
        ];

        // Log çağrısını kaldırdık
        return $menu;
    }
}
