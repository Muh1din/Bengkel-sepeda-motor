<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageService
{
    public function storeAsWebp(
        UploadedFile $image,
        string $directory
    ): string {
        if (
            ! function_exists('imagecreatefromstring') ||
            ! function_exists('imagewebp')
        ) {
            throw new RuntimeException(
                'Server belum mendukung konversi gambar ke WebP.'
            );
        }

        $imageResource = imagecreatefromstring(
            file_get_contents($image->getRealPath())
        );

        if ($imageResource === false) {
            throw new RuntimeException(
                'Gambar gagal diproses.'
            );
        }

        try {
            $filename = Str::uuid() . '.webp';

            $path = trim($directory, '/') . '/' . $filename;

            ob_start();

            $success = imagewebp($imageResource, null, 80);

            $webpContent = ob_get_clean();

            if (! $success || $webpContent === false) {
                throw new RuntimeException(
                    'Gagal mengonversi gambar ke WebP.'
                );
            }

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
