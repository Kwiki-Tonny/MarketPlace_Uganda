<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'vendor') {
            abort(403, 'Unauthorized.');
        }

        if (!$user->is_approved) {
            return redirect()->route('pending.approval');
        }

        // Check suspension
        if ($user->isSuspended()) {
            return redirect()->route('vendor.suspended')
                ->with('error', 'Your account is suspended until ' . $user->suspended_until->format('d M Y H:i'));
        }

        return $next($request);
    }
}