<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileHelper
{
    public static function saveImage(
        UploadedFile $file,
        string       $dirPath = '',
        string       $disk = 'public',
        ?int         $scaleWidth = null,
        ?int         $scaleHeight = null,
    ): string {
        $storage = Storage::disk($disk);

        $path = self::generatePath($file, $dirPath);
        // Create Intervention Image
        $imageManager = app(ImageManager::class);
        $image = $imageManager->read($file->getPathname());

        // Resize image (if image to big and keep proportions)
        $image->scale($scaleWidth, $scaleHeight);

        $storage->put($path, (string)$image->encode());

        return $path;
    }

    public static function generatePath(UploadedFile $file, string $dirPath = ''): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = implode('/', [$dirPath, $filename[0], $filename[1], $filename]);

        return $path;
    }

    /**
     * @param string|string[] $paths
     * @param string $disk
     * @return bool
     */
    public static function deleteFiles(string|array $paths, string $disk = 'public'): bool
    {
        $storage = Storage::disk($disk);

        return $storage->delete($paths);
    }
}
