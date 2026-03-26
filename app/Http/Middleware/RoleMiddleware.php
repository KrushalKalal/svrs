<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route($role . '.login');
        }

        if ($user->role !== $role) {
            // dd([
            //     'user_role' => $user->role,
            //     'required_role' => $role,
            //     'user_id' => $user->id,
            // ]);
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('member.dashboard');
        }

        return $next($request);
    }
}