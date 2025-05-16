<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRule;
use App\Http\Requests\AdoptionRuleRequest;
use Illuminate\Http\Response;

class AdoptionRuleController extends Controller
{
    /**
     * Отримати всі правила
     * Використовується для відображення списку правил на фронтенді
     */
    public function index()
    {
        $rules = AdoptionRule::orderBy('order')->get();
        return response()->json($rules);
    }

    /**
     * Створити нові правила
     */
    public function store(AdoptionRuleRequest $request)
    {
        $data = $request->validated();
        $rules = AdoptionRule::create($data);
        return response()->json($rules, Response::HTTP_CREATED);
    }

    /**
     * Оновити правила
     */
    public function update(AdoptionRuleRequest $request, AdoptionRule $rule)
    {
        $rule->update($request->validated());
        return response()->json($rule);
    }

    /**
     * Видалити правила
     */
    public function destroy(AdoptionRule $rule)
    {
        $rule->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
} 