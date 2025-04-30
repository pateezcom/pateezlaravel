<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users', 'reward_system_active')) {
        $table->boolean('reward_system_active')->default(false);
      }
      if (!Schema::hasColumn('users', 'status')) {
        $table->boolean('status')->default(true);
      }
    });
  }

  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['reward_system_active', 'status']);
    });
  }
};
