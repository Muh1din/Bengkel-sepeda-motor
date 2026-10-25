<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ImageService
{
    public function storeAsWebp(
        UploadedFile $image,
        string $directory
    ): string {
        if (
            ! function_exists('imagecreatefromstring') ||
            ! function_exists('imagewebp') ||
            ! function_exists('imagepalettetotruecolor')
        ) {
            throw new RuntimeException(
                'Server belum mendukung konversi gambar ke WebP.'
            );
        }

        $content = file_get_contents($image->getRealPath());

        if ($content === false) {
            throw new RuntimeException('Gambar gagal dibaca.');
        }

        $imageResource = imagecreatefromstring($content);

        if ($imageResource === false) {
            throw new RuntimeException('Gambar gagal diproses.');
        }

        try {
            // Konversi gambar palette menjadi true color.
            if (! imageistruecolor($imageResource)) {
                if (! imagepalettetotruecolor($imageResource)) {
                    throw new RuntimeException(
                        'Gambar gagal dikonversi ke true color.'
                    );
                }
            }

            // Pertahankan transparansi gambar.
            imagealphablending($imageResource, false);
            imagesavealpha($imageResource, true);

            ob_start();

            try {
                $success = imagewebp($imageResource, null, 80);
                $webpContent = ob_get_contents();
            } finally {
                ob_end_clean();
            }

            if (
                ! $success ||
                $webpContent === false ||
                $webpContent === ''
            ) {
                throw new RuntimeException(
                    'Gagal mengonversi gambar ke WebP.'
                );
            }

            $filename = Str::uuid() . '.webp';
            $path = trim($directory, '/') . '/' . $filename;

            if (! Storage::disk('public')->put($path, $webpContent)) {
                throw new RuntimeException(
                    'Gagal menyimpan gambar.'
                );
            }

            return $path;
        } finally {
            imagedestroy($imageResource);
        }
    }
}