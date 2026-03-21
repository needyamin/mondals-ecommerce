<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{User, Vendor};
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    use ApiResponse;

    /**
     * Apply to become a vendor.
     */
    public function apply(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user->vendor) {
            return $this->error('You already have a vendor application.', 409);
        }

        $validated = $request->validate([
            'store_name'  => 'required|string|max:255|unique:vendors,store_name',
            'description' => 'nullable|string|max:1000',
            'phone'       => 'required|string|max:20',
            'email'       => 'required|email|max:255',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
        ]);

        $vendor = Vendor::create(array_merge($validated, [
            'user_id'         => $user->id,
            'slug'            => Str::slug($validated['store_name']),
            'commission_rate' => 10.00, // Default platform rate
            'status'          => 'pending',
        ]));

        // Assign vendor role
        $user->assignRole('vendor');

        return $this->created($vendor, 'Vendor application submitted. Awaiting admin approval.');
    }

    /**
     * Get own vendor profile.
     */
    public function profile(): JsonResponse
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return $this->notFound('You are not a vendor.');
        }

        return $this->success($vendor, 'Vendor profile retrieved');
    }

    /**
     * Update vendor profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $vendor = auth()->user()->vendor;
        if (!$vendor) return $this->notFound('You are not a vendor.');

        $validated = $request->validate([
            'store_name'  => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone'       => 'sometimes|required|string|max:20',
            'logo'        => 'nullable|string|max:255',
            'banner'      => 'nullable|string|max:255',
            'city'        => 'sometimes|string|max:100',
            'country'     => 'sometimes|string|max:100',
        ]);

        if (isset($validated['store_name'])) {
            $validated['slug'] = Str::slug($validated['store_name']);
        }

        $vendor->update($validated);

        return $this->success($vendor, 'Vendor profile updated');
    }
}
