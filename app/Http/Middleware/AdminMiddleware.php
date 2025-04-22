<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Перевіряє, чи користувач є адміністратором
     * Якщо ні - повертає помилку 403 (Forbidden)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Перевіряємо, чи користувач автентифікований
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Перевіряємо, чи користувач є адміністратором
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Forbidden. Admin access required.'], 403);
        }

        return $next($request);
    }
} 