<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\MediaDisks;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final class ProductGalleryService
{
    /**
     * @return list<string>
     */
    public function syncGallery(Product $product, Request $request, bool $usePrimaryImageIndexForNewBatch = false): array
    {
        $errors = [];
        $disk = MediaDisks::productDisk() ?: 'public';

        foreach ($request->input('remove_images', []) as $imgId) {
            $img = $product->images()->find($imgId);
            if ($img) {
                if (filled($img->image)) {
                    delete_storage_path($img->image, $disk);
                }
                $img->delete();
            }
        }

        if ($request->filled('image_order')) {
            foreach ($request->input('image_order') as $i => $imgId) {
                $product->images()->where('id', $imgId)->update(['sort_order' => (int) $i]);
            }
        }

        $files = collect_request_uploads($request, 'images');

        if ($files !== []) {
            $order = (int) $product->images()->max('sort_order');
            $noPrimary = ! $product->images()->where('is_primary', true)->exists();

            $primaryIdx = null;
            if ($usePrimaryImageIndexForNewBatch && $request->has('primary_image_index')) {
                $primaryIdx = (int) $request->input('primary_image_index');
                if ($primaryIdx < 0 || $primaryIdx >= count($files)) {
                    $primaryIdx = 0;
                }
                $product->images()->update(['is_primary' => false]);
                $noPrimary = true;
            }

            foreach ($files as $idx => $file) {
                if (! $file instanceof UploadedFile || ! $file->isValid()) {
                    $errors[] = 'File #'.$idx.' invalid or missing';
                    continue;
                }

                try {
                    $path = store_disk_upload($file, upload_dir_products(), $disk);
                } catch (\Throwable $e) {
                    $errors[] = 'File #'.$idx.': '.$e->getMessage();
                    continue;
                }

                $order++;
                if ($primaryIdx !== null) {
                    $isPrimary = $idx === $primaryIdx;
                } else {
                    $isPrimary = $noPrimary && $idx === 0;
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $order,
                    'is_primary' => $isPrimary,
                ]);
                if ($isPrimary) {
                    $noPrimary = false;
                }
            }
        }

        if ($request->filled('set_primary')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $request->input('set_primary'))->update(['is_primary' => true]);
        }

        if ($product->images()->count() && ! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return $errors;
    }
}
