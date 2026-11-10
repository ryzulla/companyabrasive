<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Kategori dan produk berasal dari database/data/catalog.php + foto di storage/app/public/to_upload.
 * Perintah di bawah menggantikan seluruh kategori/produk yang ada.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (! is_dir(storage_path('app/public/to_upload'))) {
            $this->command?->warn('Folder storage/app/public/to_upload tidak ada, katalog produk dilewati.');

            return;
        }

        $this->command->call('catalog:import', ['--force' => true]);
    }
}
