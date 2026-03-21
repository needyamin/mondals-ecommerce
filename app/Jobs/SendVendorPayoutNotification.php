<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\VendorPayout;
use Illuminate\Support\Facades\Log;

class SendVendorPayoutNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payout;

    /**
     * Create a new job instance.
     */
    public function __construct(VendorPayout $payout)
    {
        $this->payout = $payout;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Placeholder for real email or SMS logic to vendor
        // Notification::send($this->payout->vendor->user, new VendorPaidNotification($this->payout));

        Log::info("Payout notification sent to vendor ID: " . $this->payout->vendor_id . " for amount: " . $this->payout->amount);
    }
}
