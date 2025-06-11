<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdoptionRequest as AdoptionRequestForm;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdoptionRequestController extends Controller
{
    /**
     * Отримати всі активні запити на усиновлення для адмін-панелі
     * Повертає список всіх запитів разом з інформацією про тварину
     */
    public function index()
    {
        $requests = AdoptionRequest::with(['user', 'animal'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    /**
     * Отримати архівовані запити
     */
    public function archived()
    {
        $requests = AdoptionRequest::with('animal')
            ->where('is_archived', true)
            ->get();
        return response()->json($requests);
    }

    /**
     * Створити новий запит на усиновлення
     * Викликається коли користувач заповнює форму на фронтенді
     */
    public function store(AdoptionRequestForm $request)
    {
        $animal = Animal::findOrFail($request->animal_id);
        
        $adoptionRequest = AdoptionRequest::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'animal_id' => $request->animal_id,
            'animal_name' => $animal->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'message' => $request->message,
            
            // 'status' => 'pending',
            'is_viewed' => false,
            'is_processed' => false,
            'is_archived' => false,
            
            // Поля, які заповнюються автоматично
            'created_at' => now()
        ]);

        LoggingService::logAdoptionRequest('created', $adoptionRequest->toArray());

        return response()->json($adoptionRequest, 201);
    }

    /**
     * Оновити статус запиту
     * Використовується в адмін-панелі для зміни статусу обробки запиту
     * Адміністратор може:
     * - Позначити запит як переглянутий (is_processed = true)
     * - Повернути запит в стан "не переглянутий" (is_processed = false)
     */
    public function update(Request $request, AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->update([
            'is_viewed' => true
        ]);

        LoggingService::logAdoptionRequest('updated', $adoptionRequest->toArray());

        return response()->json($adoptionRequest);
    }

    /**
     * Архівувати запит
     */
    public function archive(AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->update(['is_archived' => true]);
        return response()->json(['message' => 'Запит архівовано']);
    }

    /**
     * Відновити запит з архіву
     */
    public function restore(AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->update(['is_archived' => false]);
        return response()->json(['message' => 'Запит відновлено']);
    }

    /**
     * Видалити запит остаточно
     */
    public function destroy(AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function show(AdoptionRequest $adoptionRequest)
    {
        return response()->json($adoptionRequest->load(['user', 'animal']));
    }

    public function markAsViewed(AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->update(['is_viewed' => true]);
        return response()->json(['message' => 'Запит позначено як переглянутий']);
    }
}
