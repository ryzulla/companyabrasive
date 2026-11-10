<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        // Foto tunggal yang sudah ada dijadikan foto pertama (cover).
        DB::table('products')->whereNotNull('img')->where('img', '!=', '')->orderBy('id')->each(function ($p) {
            DB::table('product_images')->insert([
                'product_id' => $p->id,
                'path'       => $p->img,
                'sort'       => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
