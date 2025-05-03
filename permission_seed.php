<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$adminRole = Role::where('name', 'admin')->first();
if (!$adminRole) {
    echo "Admin rolü bulunamadı!\n";
    exit(1);
}

echo "Admin rolü bulundu (ID: " . $adminRole->id . ")\n";

// Tüm izinleri admin rolüne ata
$permissions = Permission::all();
echo $permissions->count() . " adet izin bulundu.\n";

$adminRole->syncPermissions($permissions);

echo "İzinler atandı. İzin önbelleğini temizleyin:\n";
echo "  php artisan permission:cache-reset\n";
