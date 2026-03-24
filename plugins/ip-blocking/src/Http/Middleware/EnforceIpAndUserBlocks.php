<?php

namespace Plugins\IpBlocking\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Plugins\IpBlocking\Models\BlockedIp;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class EnforceIpAndUserBlocks
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $clientIp = $request->ip() ?? '';

        if ($this->isIpBlocked($clientIp)) {
            return $this->deny($request, 'Your network is not allowed to access this site.');
        }

        $user = $request->user();
        if ($user && $user->status === User::STATUS_BANNED) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->deny($request, 'This account has been blocked.');
        }

        return $next($request);
    }

    protected function shouldSkip(Request $request): bool
    {
        return $request->is('admin', 'admin/*', 'login', 'register', 'logout')
            || $request->is('payment/*');
    }

    protected function isIpBlocked(string $clientIp): bool
    {
        if ($clientIp === '' || ! Schema::hasTable('blocked_ips')) {
            return false;
        }

        foreach (BlockedIp::query()->pluck('ip_address') as $rule) {
            $rule = trim((string) $rule);
            if ($rule === '') {
                continue;
            }
            if (str_contains($rule, '/')) {
                try {
                    if (IpUtils::checkIp($clientIp, $rule)) {
                        return true;
                    }
                } catch (\Throwable) {
                    continue;
                }
            } elseif (strcasecmp($clientIp, $rule) === 0) {
                return true;
            }
        }

        return false;
    }

    protected function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return response()
            ->view('ip-blocking::blocked', ['message' => $message], 403);
    }
}
