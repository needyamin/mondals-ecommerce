<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class MediaDisks
{
    public const GROUP = 'storage';

    /** @return list<string> */
    public static function productDiskOptions(): array
    {
        return [
            'public',
            's3',
            'r2',
            'do_spaces',
            'wasabi',
            'backblaze',
            'minio',
        ];
    }

    public static function productDisk(): string
    {
        $key = Setting::get('product_upload_disk', 'public', self::GROUP);
        return in_array($key, self::productDiskOptions(), true) ? $key : 'public';
    }

    /** Resolves public URL for a path on the configured product disk (S3 skips exists check). */
    public static function productUrl(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }
        $disk = self::productDisk();
        $driver = config("filesystems.disks.$disk.driver");
        try {
            if ($driver === 's3') {
                return Storage::disk($disk)->url($path);
            }
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->url($path);
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }
}
