<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('icon', 'logo');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('logo')->nullable()->default(null)->change();
        });

        // Existing icon values dropped — will re-seed
        DB::table('clients')->update(['logo' => null]);
    }

    public function down(): void
    {
        DB::table('clients')->whereNull('logo')->update(['logo' => 'fa-building']);

        Schema::table('clients', function (Blueprint $table) {
            $table->string('logo')->default('fa-building')->change();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('logo', 'icon');
        });
    }
};
