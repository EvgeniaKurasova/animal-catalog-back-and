<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdoptionRequestController extends Controller
{
    /**
     * Отримати всі запити на усиновлення для адмін-панелі
     * Повертає список всіх запитів разом з інформацією про тварину
     */
    public function index()
    {
        $requests = AdoptionRequest::with('animal')->get();
        return response()->json($requests);
    }

    /**
     * Створити новий запит на усиновлення
     * Викликається коли користувач заповнює форму на фронтенді
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'city' => 'nullable|string|max:255', // Змінено на необов'язкове
            'message' => 'nullable|string'
        ]);

        $request = AdoptionRequest::create($validated);

        return response()->json($request, Response::HTTP_CREATED);
    }

    /**
     * Оновити статус запиту
     * Використовується в адмін-панелі для зміни статусу обробки запиту
     * Адміністратор може:
     * - Позначити запит як оброблений (is_processed = true)
     * - Повернути запит в стан "не оброблений" (is_processed = false)
     */
    public function update(Request $request, AdoptionRequest $adoptionRequest)
    {
        $validated = $request->validate([
            'is_processed' => 'required|boolean'
        ]);

        $adoptionRequest->update($validated);

        return response()->json([
            'message' => $validated['is_processed'] 
                ? 'Запит позначено як оброблений' 
                : 'Запит повернуто в стан "не оброблений"',
            'request' => $adoptionRequest
        ]);
    }

    /**
     * Видалити запит
     * Використовується в адмін-панелі для видалення запитів
     */
    public function destroy(AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
