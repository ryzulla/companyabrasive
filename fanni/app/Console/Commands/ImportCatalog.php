<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ImageCompressor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportCatalog extends Command
{
    protected $signature = 'catalog:import
        {--source=storage/app/public/to_upload : Folder foto produk (satu folder per produk)}
        {--force : Lewati konfirmasi}';

    protected $description = 'Ganti SELURUH produk dan kategori dengan katalog di database/data/catalog.php, lengkap dengan foto terkompres';

    public function handle(): int
    {
        ini_set('memory_limit', '-1');

        $catalog = require database_path('data/catalog.php');
        $source = str_starts_with($this->option('source'), '/') ? $this->option('source') : base_path($this->option('source'));

        if (! is_dir($source)) {
            $this->error("Folder sumber tidak ditemukan: {$source}");

            return self::FAILURE;
        }

        $folders = $this->indexFolders($source);
        $missing = array_filter(array_column($catalog['products'], 'source'), fn ($s) => ! isset($folders[$this->norm($s)]));
        if ($missing) {
            $this->error('Folder foto tidak ditemukan untuk: ' . implode(', ', $missing));

            return self::FAILURE;
        }
        $unused = array_diff_key($folders, array_flip(array_map(fn ($p) => $this->norm($p['source']), $catalog['products'])));
        foreach ($unused as $name => $path) {
            $this->warn("Folder tidak dipakai di katalog: {$name}");
        }

        $this->line(sprintf('Katalog: %d kategori, %d produk. Data saat ini: %d kategori, %d produk.',
            count($catalog['categories']), count($catalog['products']), Category::count(), Product::count()));

        if (! $this->option('force') && ! $this->confirm('Semua produk dan kategori yang ada akan DIHAPUS lalu diganti. Lanjutkan?')) {
            return self::SUCCESS;
        }

        $disk = Storage::disk('public');
        $this->purgeExisting($disk);
        $disk->makeDirectory('products');
        $disk->makeDirectory('categories');

        $bar = $this->output->createProgressBar(count($catalog['products']));
        $firstImage = []; // source ternormalisasi => binary foto pertama (untuk gambar kategori)
        $totalBytes = 0;

        DB::transaction(function () use ($catalog, $folders, $disk, $bar, &$firstImage, &$totalBytes) {
            foreach ($catalog['categories'] as $c) {
                Category::create(['id' => $c['id'], 'title' => $c['title'], 'desc' => $c['desc'], 'icon' => $c['icon'] ?? null, 'img' => '']);
            }

            foreach ($catalog['products'] as $p) {
                $product = Product::create([
                    'category_id' => $p['category'], 'meta' => $p['meta'], 'title' => $p['title'], 'badge' => $p['badge'] ?? null,
                    'img' => '', 'desc' => $p['desc'], 'spec_label' => $p['spec_label'], 'spec_val' => $p['spec_val'],
                    'rpm' => $p['rpm'] ?? null, 'material' => $p['material'],
                ]);

                $files = $this->imageFiles($folders[$this->norm($p['source'])]);
                foreach ($files as $i => $file) {
                    $binary = ImageCompressor::square($file);
                    $path = "products/{$product->slug}-" . ($i + 1) . '.jpg';
                    $disk->put($path, $binary);
                    $product->images()->create(['path' => $path, 'sort' => $i + 1]);
                    $totalBytes += strlen($binary);
                    if ($i === 0) {
                        $firstImage[$this->norm($p['source'])] = $binary;
                    }
                }
                $product->syncCover();
                $bar->advance();
            }

            foreach ($catalog['categories'] as $c) {
                $binary = $firstImage[$this->norm($c['cover'])] ?? null;
                if ($binary) {
                    $path = "categories/{$c['id']}.jpg";
                    $disk->put($path, ImageCompressor::cover($binary, 900, 600));
                    Category::whereKey($c['id'])->update(['img' => $path]);
                }
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info(sprintf('Selesai: %d kategori, %d produk, %d foto (%.1f MB setelah kompresi).',
            Category::count(), Product::count(), ProductImage::count(), $totalBytes / 1048576));

        return self::SUCCESS;
    }

    /** Hapus data lama beserta file foto yang dirujuknya. */
    private function purgeExisting($disk): void
    {
        $paths = ProductImage::pluck('path')
            ->merge(Product::pluck('img'))
            ->merge(Category::pluck('img'))
            ->filter(fn ($p) => $p && ! str_starts_with($p, 'http'))
            ->unique();

        foreach ($paths as $path) {
            $disk->delete($path);
        }

        ProductImage::query()->delete();
        Product::query()->delete();
        Category::query()->delete();
    }

    /** Peta nama-folder-ternormalisasi => path, untuk setiap folder yang berisi foto. */
    private function indexFolders(string $source): array
    {
        $map = [];
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($it as $item) {
            if ($item->isDir() && $this->imageFiles($item->getPathname())) {
                $map[$this->norm($item->getFilename())] = $item->getPathname();
            }
        }

        return $map;
    }

    /** @return list<string> foto dalam satu folder, urut natural */
    private function imageFiles(string $dir): array
    {
        $files = array_values(array_filter(scandir($dir), fn ($f) => preg_match('/\.(png|jpe?g)$/i', $f)));
        natcasesort($files);

        return array_map(fn ($f) => "{$dir}/{$f}", array_values($files));
    }

    private function norm(string $name): string
    {
        return mb_strtolower(trim($name));
    }
}
