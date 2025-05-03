<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 'admin' rolünün ID'sini al
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        // Tüm izinleri getir
        $permissionIds = DB::table('permissions')->pluck('id');

        // Her izin için pivot tablosuna kayıt ekle
        foreach ($permissionIds as $permId) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permId,
                'role_id'       => $adminRoleId,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        DB::table('role_has_permissions')->where('role_id', $adminRoleId)->delete();
    }
};
