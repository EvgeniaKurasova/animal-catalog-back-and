<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdoptionRequest;
use App\Models\AdoptionRequest as AdoptionRequestModel;
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
        $requests = AdoptionRequestModel::with('animal')
            ->where('is_archived', false)
            ->get();
        return response()->json($requests);
    }

    /**
     * Отримати архівовані запити
     */
    public function archived()
    {
        $requests = AdoptionRequestModel::with('animal')
            ->where('is_archived', true)
            ->get();
        return response()->json($requests);
    }

    /**
     * Створити новий запит на усиновлення
     * Викликається коли користувач заповнює форму на фронтенді
     */
    public function store(AdoptionRequest $request)
    {
        $adoptionRequest = AdoptionRequestModel::create($request->validated());
        return $adoptionRequest->load('animal');
    }

    /**
     * Оновити статус запиту
     * Використовується в адмін-панелі для зміни статусу обробки запиту
     * Адміністратор може:
     * - Позначити запит як переглянутий (is_processed = true)
     * - Повернути запит в стан "не переглянутий" (is_processed = false)
     */
    public function update(AdoptionRequest $request, AdoptionRequestModel $adoptionRequest)
    {
        $adoptionRequest->update($request->validated());
        return $adoptionRequest->load('animal');
    }

    /**
     * Архівувати запит
     */
    public function archive(AdoptionRequestModel $adoptionRequest)
    {
        $adoptionRequest->update(['is_archived' => true]);
        return response()->json(['message' => 'Запит архівовано']);
    }

    /**
     * Відновити запит з архіву
     */
    public function restore(AdoptionRequestModel $adoptionRequest)
    {
        $adoptionRequest->update(['is_archived' => false]);
        return response()->json(['message' => 'Запит відновлено']);
    }

    /**
     * Видалити запит остаточно
     */
    public function destroy(AdoptionRequestModel $adoptionRequest)
    {
        $adoptionRequest->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
