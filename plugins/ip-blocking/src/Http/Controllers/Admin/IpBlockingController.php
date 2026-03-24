<?php

namespace Plugins\IpBlocking\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Plugins\IpBlocking\Models\BlockedIp;

class IpBlockingController extends Controller
{
    public function index()
    {
        $ips = BlockedIp::query()->with('creator')->orderByDesc('id')->paginate(15, ['*'], 'ip_page');
        $bannedUsers = User::query()
            ->where('status', User::STATUS_BANNED)
            ->orderByDesc('updated_at')
            ->paginate(15, ['*'], 'user_page');

        return view('ip-blocking::admin.index', compact('ips', 'bannedUsers'));
    }

    public function storeIp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'ip_address' => ['required', 'string', 'max:64', Rule::unique('blocked_ips', 'ip_address')],
            'note' => ['nullable', 'string', 'max:500'],
        ]);
        $v->after(function ($validator) use ($request) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }
            if (! $this->validIpOrCidr($request->input('ip_address'))) {
                $validator->errors()->add('ip_address', 'Enter a valid IP address or CIDR range (e.g. 203.0.113.0/24).');
            }
        });
        $v->validate();

        BlockedIp::create([
            'ip_address' => trim($request->input('ip_address')),
            'note' => $request->input('note'),
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'IP rule added.');
    }

    public function destroyIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return back()->with('success', 'IP rule removed.');
    }

    public function banUser(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->with('error', 'No user found with that email.');
        }
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Cannot ban an admin account from here.');
        }
        $user->update(['status' => User::STATUS_BANNED]);

        return back()->with('success', 'User has been banned.');
    }

    public function unbanUser(User $user)
    {
        if ($user->status !== User::STATUS_BANNED) {
            return back()->with('error', 'User is not banned.');
        }
        $user->update(['status' => User::STATUS_ACTIVE]);

        return back()->with('success', 'User has been unbanned.');
    }

    protected function validIpOrCidr(string $value): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }
        if (! str_contains($value, '/')) {
            return filter_var($value, FILTER_VALIDATE_IP) !== false;
        }
        [$net, $mask] = explode('/', $value, 2);
        if (filter_var($net, FILTER_VALIDATE_IP) === false || ! is_numeric($mask)) {
            return false;
        }
        $mask = (int) $mask;
        if (filter_var($net, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $mask >= 0 && $mask <= 32;
        }

        return $mask >= 0 && $mask <= 128;
    }
}
