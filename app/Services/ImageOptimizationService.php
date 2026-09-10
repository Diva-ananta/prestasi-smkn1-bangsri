<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageOptimizationService
{
    public function storePrestasi(UploadedFile $file): string
    {
        return $this->store($file, 'foto-prestasi', 2000, 2000, 80, 2 * 1024 * 1024, 4 * 1024 * 1024);
    }

    public function storeSiswa(UploadedFile $file): string
    {
        return $this->store($file, 'foto-siswa', 600, 800, 85, 300 * 1024, 4 * 1024 * 1024);
    }

    private function store(
        UploadedFile $file,
        string $directory,
        int $maxWidth,
        int $maxHeight,
        int $initialQuality,
        int $targetBytes,
        int $maxBytes
    ): string {
        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo || !in_array($imageInfo['mime'], ['image/jpeg', 'image/png'], true)) {
            throw new RuntimeException('File gambar tidak dapat diproses.');
        }

        $source = match ($imageInfo['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png' => @imagecreatefrompng($file->getRealPath()),
        };

        if (!$source) {
            throw new RuntimeException('File gambar tidak dapat dibaca.');
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = min(1, $maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));
        $image = imagecreatetruecolor($width, $height);

        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
        imagedestroy($source);

        $contents = null;
        for ($quality = $initialQuality; $quality >= 20; $quality -= 5) {
            ob_start();
            imagewebp($image, null, $quality);
            $contents = ob_get_clean();

            if ($contents !== false && strlen($contents) <= $targetBytes) {
                break;
            }
        }
        imagedestroy($image);

        if ($contents === false || $contents === null || strlen($contents) > $maxBytes) {
            throw new RuntimeException('Ukuran gambar hasil kompresi masih terlalu besar.');
        }

        $path = $directory . '/' . Str::uuid() . '.webp';
        if (!Storage::disk('public')->put($path, $contents)) {
            throw new RuntimeException('Gambar hasil kompresi gagal disimpan.');
        }

        return $path;
    }
}