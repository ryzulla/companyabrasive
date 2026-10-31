<?php

namespace App\Support;

use RuntimeException;

/** Kompres foto produk dengan GD: persegi, JPEG, latar mengikuti warna tepi gambar. */
class ImageCompressor
{
    /** Gambar persegi $size × $size (objek utuh, tidak terpotong), dikembalikan sebagai binary JPEG. */
    public static function square(string $path, int $size = 1000, int $quality = 82): string
    {
        $im = self::load($path);
        $w = imagesx($im);
        $h = imagesy($im);

        [$r, $g, $b] = self::edgeColor($im, $w, $h);

        $canvas = imagecreatetruecolor($size, $size);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, $r, $g, $b));

        $scale = min($size / $w, $size / $h);
        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);
        imagealphablending($canvas, true);
        imagecopyresampled($canvas, $im, (int) (($size - $nw) / 2), (int) (($size - $nh) / 2), 0, 0, $nw, $nh, $w, $h);

        $out = self::encode($canvas, $quality);
        imagedestroy($im);
        imagedestroy($canvas);

        return $out;
    }

    /** Potong tengah ke ukuran $w × $h (untuk gambar kategori), dari binary JPEG. */
    public static function cover(string $jpegBinary, int $w, int $h, int $quality = 82): string
    {
        $src = imagecreatefromstring($jpegBinary);
        $sw = imagesx($src);
        $sh = imagesy($src);
        $scale = max($w / $sw, $h / $sh);
        $cw = (int) round($w / $scale);
        $ch = (int) round($h / $scale);

        $canvas = imagecreatetruecolor($w, $h);
        imagecopyresampled($canvas, $src, 0, 0, (int) (($sw - $cw) / 2), (int) (($sh - $ch) / 2), $w, $h, $cw, $ch);

        $out = self::encode($canvas, $quality);
        imagedestroy($src);
        imagedestroy($canvas);

        return $out;
    }

    private static function load(string $path)
    {
        $im = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => @imagecreatefrompng($path),
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            default => false,
        };
        if (! $im) {
            throw new RuntimeException("Gagal membaca gambar: {$path}");
        }

        if (preg_match('/\.jpe?g$/i', $path) && function_exists('exif_read_data')) {
            $orientation = @exif_read_data($path)['Orientation'] ?? 1;
            $im = match ($orientation) {
                3 => imagerotate($im, 180, 0),
                6 => imagerotate($im, -90, 0),
                8 => imagerotate($im, 90, 0),
                default => $im,
            };
        }

        return $im;
    }

    /** Rata-rata warna piksel tepi (transparan dianggap putih); hampir putih → putih murni. */
    private static function edgeColor($im, int $w, int $h): array
    {
        $r = $g = $b = $n = 0;
        $step = max(1, (int) (max($w, $h) / 200));
        $sample = function (int $x, int $y) use ($im, &$r, &$g, &$b, &$n) {
            $c = imagecolorat($im, $x, $y);
            $transparent = (($c >> 24) & 127) > 100;
            $r += $transparent ? 255 : ($c >> 16) & 255;
            $g += $transparent ? 255 : ($c >> 8) & 255;
            $b += $transparent ? 255 : $c & 255;
            $n++;
        };
        for ($x = 0; $x < $w; $x += $step) {
            $sample($x, 0);
            $sample($x, $h - 1);
        }
        for ($y = 0; $y < $h; $y += $step) {
            $sample(0, $y);
            $sample($w - 1, $y);
        }
        [$r, $g, $b] = [(int) ($r / $n), (int) ($g / $n), (int) ($b / $n)];

        return ($r > 240 && $g > 240 && $b > 240) ? [255, 255, 255] : [$r, $g, $b];
    }

    private static function encode($canvas, int $quality): string
    {
        imageinterlace($canvas, true);
        ob_start();
        imagejpeg($canvas, null, $quality);

        return ob_get_clean();
    }
}
