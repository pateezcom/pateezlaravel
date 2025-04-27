<?php

use Illuminate\Support\Facades\DB;

/*
 * Bu dosya, language tablosundaki icon değerlerini Vuexy tabler icon formatında güncellemek için kullanılabilir.
 * Bu dosyayı projenizin kök dizininde çalıştırabilirsiniz: php update_language_icons.php
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// English için icon değerini güncelleme
DB::table('languages')
    ->where('id', 9)
    ->update(['icon' => 'ti ti-flag-usa']);

// Turkish için icon değerini güncelleme 
DB::table('languages')
    ->where('id', 12)
    ->update(['icon' => 'ti ti-flag-turkey']);

echo "Languages tablosundaki icon değerleri Tabler ikonlarına uygun olarak güncellendi.\n";
echo "English: ti ti-flag-usa\n";
echo "Turkish: ti ti-flag-turkey\n";
