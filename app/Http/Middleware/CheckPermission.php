<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!$request->user()?->can($permission)) {
            abort(403, 'No tiene permisos suficientes.');
        }

        return $next($request);
    }
}
