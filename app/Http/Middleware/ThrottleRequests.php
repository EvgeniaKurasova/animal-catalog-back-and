<?php

namespace App\Http\Middleware;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;

class ThrottleRequests extends BaseThrottleRequests
{
    /**
     * Створення нового екземпляру middleware
     */
    public function __construct(RateLimiter $limiter)
    {
        parent::__construct($limiter);
    }

    /**
     * Отримання повідомлення про перевищення ліміту
     */
    protected function buildResponse($key, $maxAttempts): Response
    {
        return response()->json([
            'message' => 'Забагато запитів. Спробуйте пізніше.',
        ], 429);
    }
} 