<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorSignupController extends Controller
{
    public function create()
    {
        if (auth()->check()) {
            return redirect()->route('home')
                ->with('info', 'Sign out first if you need to register a new seller account.');
        }

        return view('auth.register-vendor');
    }

    public function store(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('home')
                ->with('info', 'Sign out first to register a new seller account.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'store_name'  => 'required|string|max:255|unique:vendors,store_name',
            'description' => 'nullable|string|max:1000',
            'phone'       => 'required|string|max:20',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
            'terms'       => 'accepted',
        ]);

        $slug = $this->uniqueSlug(Str::slug($validated['store_name']));

        $user = DB::transaction(function () use ($validated, $slug) {
            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'phone'             => $validated['phone'],
                'status'            => 'active',
                'email_verified_at' => now(),
            ]);

            Vendor::create([
                'user_id'         => $user->id,
                'store_name'      => $validated['store_name'],
                'slug'            => $slug,
                'description'     => $validated['description'] ?? null,
                'phone'           => $validated['phone'],
                'email'           => $validated['email'],
                'city'            => $validated['city'],
                'country'         => $validated['country'],
                'commission_rate' => 10.00,
                'status'          => 'pending',
            ]);

            $user->assignRole('vendor');

            return $user;
        });

        Auth::login($user);

        return redirect()->route('home')->with(
            'success',
            'Seller application submitted. We will notify you when your store is approved.'
        );
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'store-'.Str::lower(Str::random(8));
        $i = 0;
        while (Vendor::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
