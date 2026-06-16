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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $userRole = $request->user()->role;
        $allowedRoles = explode('|', $roles);

        $isDynamicRoleValid = \Schema::hasTable('roles') && \DB::table('roles')->where('name', $userRole)->exists();

        if (in_array($userRole, $allowedRoles) || $isDynamicRoleValid) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }
}
