<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if ($request->user() && $request->user()->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak akses (Permission: ' . $permission . ') untuk halaman ini!');
    }
}
