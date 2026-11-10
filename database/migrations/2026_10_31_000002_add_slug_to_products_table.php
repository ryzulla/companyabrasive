<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        $used = [];
        DB::table('products')->orderBy('id')->each(function ($p) use (&$used) {
            $base = Str::slug($p->title) ?: 'produk';
            $slug = $base;
            if (isset($used[$slug]) || ctype_digit($slug)) {
                $slug = "{$base}-{$p->id}";
            }
            $used[$slug] = true;
            DB::table('products')->where('id', $p->id)->update(['slug' => $slug]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
