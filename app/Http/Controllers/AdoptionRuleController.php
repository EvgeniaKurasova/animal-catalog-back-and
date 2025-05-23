<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRule;
use App\Http\Requests\AdoptionRuleRequest;
use App\Services\LoggingService;
use Illuminate\Http\Response;

class AdoptionRuleController extends Controller
{
    /**
     * Отримати всі правила
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
        
        LoggingService::logError('Adoption rule created', $rules->toArray());
        
        return response()->json($rules, Response::HTTP_CREATED);
    }

    /**
     * Оновити правила
     */
    public function update(AdoptionRuleRequest $request, AdoptionRule $rule)
    {
        $rule->update($request->validated());
        
        LoggingService::logError('Adoption rule updated', $rule->toArray());
        
        return response()->json($rule);
    }

    /**
     * Видалити правила
     */
    public function destroy(AdoptionRule $rule)
    {
        LoggingService::logError('Adoption rule deleted', $rule->toArray());
        
        $rule->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
} 