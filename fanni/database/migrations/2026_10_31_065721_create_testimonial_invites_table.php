<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Testimoni yang sudah ada tetap tampil (approved); kiriman klien masuk sebagai pending
            $table->string('status', 20)->default('approved')->after('pos')->index();
        });

        Schema::create('testimonial_invites', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('label');                       // catatan untuk admin, mis. "PT Contoh - Pak Budi"
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('testimonial_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonial_invites');
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
