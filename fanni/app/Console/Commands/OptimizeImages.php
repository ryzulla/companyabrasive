<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--dry-run : Hanya hitung penghematan, tanpa mengubah file} {--no-backup : Jangan salin file asli ke storage/app/image-backup}';
    protected $description = 'Perkecil semua foto di storage/app/public (maks. 1600px, kompresi 82%) tanpa mengubah nama file.';

    public function handle(): int
    {
        $root = Storage::disk('public')->path('');
        $backup = storage_path('app/image-backup');
        $dry = (bool) $this->option('dry-run');
        $totalBefore = $totalAfter = $changed = 0;

        foreach (File::allFiles($root) as $f) {
            if (! preg_match('/\.(jpe?g|png|webp)$/i', $f->getFilename())) {
                continue;
            }
            $rel = $f->getRelativePathname();

            // Folder staging berisi foto asli untuk diunggah; jangan disentuh
            if (str_starts_with($rel, 'to_upload')) {
                continue;
            }

            if (! $dry && ! $this->option('no-backup')) {
                File::ensureDirectoryExists(dirname("$backup/$rel"));
                if (! file_exists("$backup/$rel")) {
                    copy($f->getPathname(), "$backup/$rel");
                }
            }

            [$before, $after] = ImageOptimizer::optimizeFile($f->getPathname(), ImageOptimizer::MAX_SIDE, $dry);
            $totalBefore += $before;
            $totalAfter += $after;
            if ($after < $before) {
                $changed++;
            }
        }

        $this->info(sprintf(
            '%s%d file diperkecil: %s → %s (hemat %s).',
            $dry ? '[dry-run] ' : '', $changed,
            self::fmt($totalBefore), self::fmt($totalAfter), self::fmt($totalBefore - $totalAfter)
        ));

        return self::SUCCESS;
    }

    private static function fmt(int $b): string
    {
        return $b > 1048576 ? round($b / 1048576, 1) . ' MB' : round($b / 1024) . ' KB';
    }
}
