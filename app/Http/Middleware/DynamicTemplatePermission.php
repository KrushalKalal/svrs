<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DynamicTemplatePermission
{
    public function handle(Request $request, Closure $next)
    {
        $permission = $request->route('name'); 
        $user = $request->user();
        if (!$user || !$user->can($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
