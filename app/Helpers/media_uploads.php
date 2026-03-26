<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('public_upload_root')) {
    /** Absolute path: storage/app/public */
    function public_upload_root(): string
    {
        return storage_path('app/public');
    }
}

if (! function_exists('upload_dir_products')) {
    function upload_dir_products(): string
    {
        return 'products';
    }
}

if (! function_exists('upload_dir_vendor_logos')) {
    function upload_dir_vendor_logos(): string
    {
        return 'vendors/logos';
    }
}

if (! function_exists('upload_dir_vendor_banners')) {
    function upload_dir_vendor_banners(): string
    {
        return 'vendors/banners';
    }
}

if (! function_exists('store_public_upload')) {
    /**
     * Save an uploaded file under storage/app/public/{directory} and return the relative path for the database.
     *
     * @param  string  $directory  e.g. "products", "vendors/logos" (no leading slash)
     */
    function store_public_upload(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $destDir = public_upload_root().DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $directory);
        File::ensureDirectoryExists($destDir);

        $ext = $file->guessExtension() ?: 'bin';
        $name = Str::random(40).'.'.$ext;
        $file->move($destDir, $name);

        return $directory.'/'.$name;
    }
}

if (! function_exists('store_disk_upload')) {
    /** Store on Laravel disk; `public` uses local store_public_upload path layout. */
    function store_disk_upload(UploadedFile $file, string $directory, string $disk): string
    {
        $directory = trim($directory, '/');
        if ($disk === 'public') {
            return store_public_upload($file, $directory);
        }
        $path = Storage::disk($disk)->putFile($directory, $file);
        if ($path === false) {
            throw new \RuntimeException('Could not store upload.');
        }

        return $path;
    }
}

if (! function_exists('delete_storage_path')) {
    /**
     * Delete a file from a storage disk. Skips null, empty, and full URLs.
     */
    function delete_storage_path(?string $path, string $disk = 'public'): void
    {
        if ($path === null || $path === '') {
            return;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return;
        }
        try {
            Storage::disk($disk)->delete($path);
        } catch (\Throwable) {
        }
    }
}

if (! function_exists('collect_request_uploads')) {
    /**
     * @return list<UploadedFile>
     */
    function collect_request_uploads(Request $request, string $key): array
    {
        $files = $request->file($key);
        if ($files === null) {
            return [];
        }

        if (! is_array($files)) {
            $files = [$files];
        }

        $out = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $out[] = $file;
            }
        }

        return $out;
    }
}

if (! function_exists('is_remote_media_url')) {
    function is_remote_media_url(?string $path): bool
    {
        return filled($path) && filter_var($path, FILTER_VALIDATE_URL);
    }
}
