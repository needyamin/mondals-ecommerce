<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ExportsToCsv
{
    /**
     * Generic CSV export.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $filename
     * @param array $mapping [column_name => attribute_name or closure]
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function exportCsv($query, string $filename, array $mapping)
    {
        $filename = Str::slug($filename) . '-' . now()->format('Y-m-d-His') . '.csv';

        // Get all results for export
        $items = $query->latest()->get();

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility with UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            return $file;
        };

        return response()->stream(function() use ($items, $mapping) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, array_keys($mapping));
            
            foreach ($items as $item) {
                $row = [];
                foreach ($mapping as $column => $attribute) {
                    if ($attribute instanceof \Closure) {
                        $row[] = $attribute($item);
                    } else {
                        $row[] = data_get($item, $attribute);
                    }
                }
                fputcsv($file, $row);
            }
            
            fclose($file);
        }, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}
