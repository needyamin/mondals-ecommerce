<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImageScaling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imagePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Placeholder for real image resizing logic
        // e.g., Intervention Image wrapper generating multiple sizes:
        // Image::make(storage_path('app/' . $this->imagePath))->resize(300, 300)->save(...);

        Log::info("Image processed and scaled: " . $this->imagePath);
    }
}
