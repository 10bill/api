<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
{
    $user = auth()->user();
    if ($user && ($user->role === 'admin' || $user->role === 'super_admin')) {
        return $next($request);
    }

    return response()->json(['message' => 'Accès non autorisé.'], 403);
}

}