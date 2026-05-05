<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $role = in_array($request->user()->role, explode('|', $roles));
        if ($role) {
            return $next($request);
        }
        abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }
}
