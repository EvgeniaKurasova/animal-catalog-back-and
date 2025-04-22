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
        // Перевіряємо, чи автентифікований користувач
        if (!$request->user()) {
            return response()->json([
                'message' => 'Необхідна автентифікація'
            ], 401);
        }

        // Перевіряємо, чи користувач є адміністратором
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Доступ заборонено. Потрібні права адміністратора.'
            ], 403);
        }

        return $next($request);
    }
}
