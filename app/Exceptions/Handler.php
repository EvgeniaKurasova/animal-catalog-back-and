<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * Список винятків, які не будуть логуватися
     */
    protected $dontReport = [
        //
    ];

    /**
     * Список винятків, які не будуть відображатися користувачу
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Реєстрація обробників винятків
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Обробка помилок автентифікації
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'status' => 'error'
                ], 401);
            }
        });

        // Обробка помилок валідації
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                    'status' => 'error'
                ], 422);
            }
        });

        // Обробка помилок "не знайдено"
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Resource not found.',
                    'status' => 'error'
                ], 404);
            }
        });

        // Обробка помилок маршрутизації
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'The requested endpoint does not exist.',
                    'status' => 'error'
                ], 404);
            }
        });

        // Обробка помилок методу
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Method not allowed.',
                    'status' => 'error'
                ], 405);
            }
        });

        // Обробка помилок авторизації
        $this->renderable(function (AuthorizationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'This action is unauthorized.',
                    'status' => 'error'
                ], 403);
            }
        });

        // Обробка всіх інших помилок
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'message' => $e->getMessage(),
                    'status' => 'error',
                    'trace' => config('app.debug') ? $e->getTrace() : null
                ], $status);
            }
        });
    }

    /**
     * Обробка винятків та формування відповіді
     */
    public function render($request, Throwable $e): JsonResponse
    {
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Помилка валідації',
                    'errors' => $e->errors(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Необхідна авторизація',
                ], 401);
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Запитуваний ресурс не знайдено',
                ], 404);
            }

            // Для всіх інших помилок
            return response()->json([
                'message' => 'Сталася помилка. Будь ласка, спробуйте пізніше.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        return parent::render($request, $e);
    }

    public function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest(route('login'));
    }
} 