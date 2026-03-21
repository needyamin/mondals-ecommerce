<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    use ApiResponse;

    /**
     * Get all addresses for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $addresses = Address::where('user_id', auth()->id())->latest()->get();
        return $this->success($addresses, 'Addresses retrieved successfully');
    }

    /**
     * Store a new address.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label'               => 'required|string|max:50',
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'phone'               => 'required|string|max:20',
            'address_line_1'      => 'required|string|max:255',
            'address_line_2'      => 'nullable|string|max:255',
            'city'                => 'required|string|max:100',
            'state'               => 'nullable|string|max:100',
            'zip_code'            => 'nullable|string|max:20',
            'country'             => 'required|string|max:100',
            'is_default_shipping' => 'boolean',
            'is_default_billing'  => 'boolean',
        ]);

        // Manage defaults
        if (!empty($validated['is_default_shipping'])) {
            Address::where('user_id', auth()->id())->update(['is_default_shipping' => false]);
        }
        if (!empty($validated['is_default_billing'])) {
            Address::where('user_id', auth()->id())->update(['is_default_billing' => false]);
        }

        $address = auth()->user()->addresses()->create($validated);

        return $this->created($address, 'Address created successfully');
    }

    /**
     * Update an existing address.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'label'               => 'sometimes|required|string|max:50',
            'first_name'          => 'sometimes|required|string|max:100',
            'last_name'           => 'sometimes|required|string|max:100',
            'phone'               => 'sometimes|required|string|max:20',
            'address_line_1'      => 'sometimes|required|string|max:255',
            'address_line_2'      => 'nullable|string|max:255',
            'city'                => 'sometimes|required|string|max:100',
            'state'               => 'nullable|string|max:100',
            'zip_code'            => 'nullable|string|max:20',
            'country'             => 'sometimes|required|string|max:100',
            'is_default_shipping' => 'boolean',
            'is_default_billing'  => 'boolean',
        ]);

        if (!empty($validated['is_default_shipping']) && $validated['is_default_shipping']) {
            Address::where('user_id', auth()->id())->where('id', '!=', $id)->update(['is_default_shipping' => false]);
        }
        if (!empty($validated['is_default_billing']) && $validated['is_default_billing']) {
            Address::where('user_id', auth()->id())->where('id', '!=', $id)->update(['is_default_billing' => false]);
        }

        $address->update($validated);

        return $this->success($address, 'Address updated successfully');
    }

    /**
     * Delete an address.
     */
    public function destroy(int $id): JsonResponse
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return $this->success(null, 'Address deleted successfully');
    }
}
