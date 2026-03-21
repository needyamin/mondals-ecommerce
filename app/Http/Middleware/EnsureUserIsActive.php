<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     * Blocks banned or inactive users from accessing the application.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->isActive()) {
            // Log the user out
            auth()->logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account has been deactivated. Please contact support.'
                ], 403);
            }

            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
