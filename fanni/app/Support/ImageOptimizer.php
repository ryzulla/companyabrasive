<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Memperkecil foto yang diunggah (maks. sisi terpanjang + kompresi) tanpa mengganti format,
 * sehingga ekstensi dan path di database tetap sama.
 */
class ImageOptimizer
{
    public const MAX_SIDE = 1600;
    private const QUALITY = 82;

    /** Simpan unggahan ke disk `public` setelah dioptimalkan; mengembalikan path relatif. */
    public static function store(UploadedFile $file, string $dir, int $maxSide = self::MAX_SIDE): string
    {
        $path = $file->store($dir, 'public');
        self::optimizeFile(Storage::disk('public')->path($path), $maxSide);

        return $path;
    }

    /**
     * Optimalkan file di tempat. Mengembalikan [ukuran_lama, ukuran_baru].
     * File hanya ditimpa kalau hasilnya benar-benar lebih kecil.
     */
    public static function optimizeFile(string $file, int $maxSide = self::MAX_SIDE, bool $dryRun = false): array
    {
        $before = @filesize($file) ?: 0;
        $info = @getimagesize($file);
        if (! $info || ! function_exists('imagecreatetruecolor')) {
            return [$before, $before];
        }

        [$w, $h, $type] = $info;

        // Foto 12 MP+ butuh ±50 MB per salinan di memori; beri ruang agar tidak gagal diam-diam
        $needed = (int) ($w * $h * 4 * 3.2) + 32 * 1048576;
        $limit = self::memoryLimitBytes();
        if ($limit !== -1 && $needed > $limit) {
            @ini_set('memory_limit', (string) $needed);
            if (self::memoryLimitBytes() !== -1 && self::memoryLimitBytes() < $needed) {
                return [$before, $before]; // tidak cukup memori: biarkan file asli
            }
        }
        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file),
            IMAGETYPE_PNG  => @imagecreatefrompng($file),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file),
            default        => false, // GIF/SVG dll. dibiarkan
        };
        if (! $src) {
            return [$before, $before];
        }

        // Putar sesuai EXIF (foto dari HP) lalu hapus metadata dengan menyimpan ulang
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $orientation = @exif_read_data($file)['Orientation'] ?? 1;
            $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
            if ($angle) {
                $rotated = imagerotate($src, $angle, 0);
                imagedestroy($src);
                $src = $rotated;
                [$w, $h] = [imagesx($src), imagesy($src)];
            }
        }

        $scale = min(1, $maxSide / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        if ($type !== IMAGETYPE_JPEG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        $tmp = $file . '.opt';
        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($dst, $tmp, self::QUALITY),
            IMAGETYPE_PNG  => imagepng($dst, $tmp, 9),
            IMAGETYPE_WEBP => imagewebp($dst, $tmp, self::QUALITY),
        };
        imagedestroy($src);
        imagedestroy($dst);

        $after = @filesize($tmp) ?: $before;
        // Hanya ganti file kalau hasilnya benar-benar lebih kecil
        if ($after < $before) {
            $dryRun ? @unlink($tmp) : rename($tmp, $file);
        } else {
            @unlink($tmp);
            $after = $before;
        }

        return [$before, $after];
    }

    private static function memoryLimitBytes(): int
    {
        $v = trim((string) ini_get('memory_limit'));
        if ($v === '-1') {
            return -1;
        }
        $n = (int) $v;

        return match (strtolower(substr($v, -1))) {
            'g' => $n * 1073741824,
            'm' => $n * 1048576,
            'k' => $n * 1024,
            default => $n,
        };
    }
}
