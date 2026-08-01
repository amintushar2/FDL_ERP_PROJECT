<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $route = '/' . ltrim($request->path(), '/');

        $hasPermission = DB::table('ALL_USER_SUB_DETAILS')
            ->where('USER_ID', $user->user_id)
            ->where('ROUTE', $route)
            ->where('ENABLED', 'Y')
            ->exists();

        if (!$hasPermission) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}