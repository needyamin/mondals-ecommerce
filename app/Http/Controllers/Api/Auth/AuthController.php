<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{LoginRequest, RegisterRequest};
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register a new user (customer).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'status'   => User::STATUS_ACTIVE,
        ]);

        // Default registration is customer
        $user->assignRole('customer');

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->created([
            'user'  => $user->load('roles:name'),
            'token' => $token,
        ], 'Registration successful');
    }

    /**
     * Authenticate user and return token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isActive()) {
            return $this->forbidden('Your account is deactivated or banned.');
        }

        // Delete old tokens to prevent buildup if not remembering
        if (!$request->remember) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user'  => $user->load('roles:name'),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(): JsonResponse
    {
        return $this->success(auth()->user()->load(['roles:name', 'vendor']));
    }

    /**
     * Revoke tokens and logout.
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->success(null, 'Logged out successfully');
    }
}
