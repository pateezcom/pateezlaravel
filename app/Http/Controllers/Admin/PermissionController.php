<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Check if a user has permission based on slug.
     * If slug ends with .read or .full, check directly.
     * Otherwise, check either slug + .read or slug + .full.
     * Super Admin and admin roles bypass check.
     *
     * @param string|null $slug
     * @return bool
     */
    public static function checkRoutePermission($slug = null)
    {
        if (! Auth::check()) {
            return false;
        }
        // Super Admin veya admin rolü tam yetki
        if (Auth::user()->hasAnyRole(['Super Admin', 'admin'])) {
            return true;
        }
        if (empty($slug)) {
            return true;
        }
        // Eğer slug zaten izin suffix içeriyorsa
        if (Str::endsWith($slug, ['.read', '.full'])) {
            return Auth::user()->can($slug);
        }
        // Okuma veya tam izin kontrolü
        return Auth::user()->hasAnyPermission(["{$slug}.read", "{$slug}.full"]);
    }

    /**
     * Get the base slug for the current route
     * Maps routes like admin.users.* to admin.users
     *
     * @return string|null
     */
    public static function getPermissionForCurrentRoute()
    {
        $routeName = Route::currentRouteName();
        $slugs = [
            'admin.settings.languages',
            'admin.settings',
            'admin.role.permissions',
            'admin.users',
        ];
        foreach ($slugs as $slug) {
            if (strpos($routeName, $slug) === 0) {
                return $slug;
            }
        }
        return null;
    }
}
