<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Перевіряє, чи є користувач адміністратором
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Якщо користувач не автентифікований або не адмін
        if (!$request->user() || !$request->user()->isAdmin) {
            return response()->json([
                'message' => 'Доступ заборонено'
            ], 403);
        }

        return $next($request);
    }
}
