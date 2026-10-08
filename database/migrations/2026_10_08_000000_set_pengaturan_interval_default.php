<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pengaturan')->whereNull('interval')->update(['interval' => 10]);

        Schema::table('pengaturan', function (Blueprint $table) {
            $table->integer('interval')->nullable(false)->default(10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->integer('interval')->change();
        });
    }
};
