<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Allow the request only for logged-in, active users that have one of the given roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'This account is suspended.']);
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
