<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite: recreate table with logo as nullable string
        Schema::create('clients_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Copy existing rows (icon value dropped — will re-seed)
        DB::statement('INSERT INTO clients_new (id, name, logo, "order", created_at, updated_at)
                       SELECT id, name, NULL, "order", created_at, updated_at FROM clients');

        Schema::drop('clients');
        DB::statement('ALTER TABLE clients_new RENAME TO clients');
    }

    public function down(): void
    {
        Schema::create('clients_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('fa-building');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        DB::statement('INSERT INTO clients_old (id, name, icon, "order", created_at, updated_at)
                       SELECT id, name, COALESCE(logo, \'fa-building\'), "order", created_at, updated_at FROM clients');

        Schema::drop('clients');
        DB::statement('ALTER TABLE clients_old RENAME TO clients');
    }
};
