<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs_new', function (Blueprint $table) {
            $table->id();
            $table->string('img')->nullable();
            $table->string('meta');
            $table->string('title');
            $table->text('desc');
            $table->timestamps();
        });

        DB::statement('INSERT INTO blogs_new SELECT * FROM blogs');
        Schema::drop('blogs');
        DB::statement('ALTER TABLE blogs_new RENAME TO blogs');
    }

    public function down(): void
    {
        // SQLite does not support reverting nullable easily
    }
};
