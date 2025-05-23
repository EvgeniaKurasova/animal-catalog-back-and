<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    public static function logAnimalAction(string $action, array $data): void
    {
        Log::info("Animal {$action}", [
            'animal_id' => $data['animal_id'] ?? null,
            'name' => $data['name'] ?? null,
            'type' => $data['type'] ?? null,
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]);
    }

    public static function logAdoptionRequest(string $action, array $data): void
    {
        Log::info("Adoption Request {$action}", [
            'request_id' => $data['request_id'] ?? null,
            'animal_id' => $data['animal_id'] ?? null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'status' => $data['status'] ?? null,
            'timestamp' => now(),
        ]);
    }

    public static function logError(string $message, array $context = []): void
    {
        Log::error($message, array_merge($context, [
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]));
    }
} 