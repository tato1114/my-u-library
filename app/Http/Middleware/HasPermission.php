<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route_name = $request->route()->getName();
        $loggedin_user = Auth::user();

        return $loggedin_user->can($route_name)
        ? $next($request)
        : response()->json(['error' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);

    }
}
