<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
    /**
     * Handle an incoming request.
     * Only allows users with 'vendor' role and an approved vendor profile.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasRole('vendor')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Vendor access required.'], 403);
            }
            abort(403, 'Forbidden. Vendor access required.');
        }

        // Check if vendor profile is approved
        $vendor = $request->user()->vendor;
        if (!$vendor || $vendor->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your vendor account is not yet approved.'], 403);
            }
            abort(403, 'Your vendor account is not yet approved.');
        }

        return $next($request);
    }
}
