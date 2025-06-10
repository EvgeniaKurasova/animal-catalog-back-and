<?php

namespace App\Http\Controllers;

use App\Models\ShelterInfo;
use App\Http\Requests\ShelterInfoRequest;
use App\Services\LoggingService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ShelterInfoController extends Controller
{
    /**
     * Отримати інформацію про притулок
     */
    public function show(): JsonResponse
    {
        $shelterInfo = CacheService::getShelterInfo();
        
        if (!$shelterInfo) {
            return response()->json(['message' => 'Інформацію про притулок не знайдено'], 404);
        }

        return response()->json($shelterInfo);
    }

    /**
     * Створити інформацію про притулок
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'working_hours' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|json',
            'photos' => 'nullable|array',
            'photos.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shelterInfo = ShelterInfo::create($request->all());

        return response()->json([
            'message' => 'Інформацію про притулок успішно створено',
            'data' => $shelterInfo
        ], 201);
    }

    /**
     * Оновити інформацію про притулок
     */
    public function update(Request $request, ShelterInfo $shelterInfo)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'working_hours' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|json',
            'photos' => 'nullable|array',
            'photos.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shelterInfo->update($request->all());

            return response()->json([
            'message' => 'Інформацію про притулок успішно оновлено',
            'data' => $shelterInfo
        ]);
    }

    /**
     * Видалити інформацію про притулок
     */
    public function destroy(ShelterInfo $shelterInfo)
    {
        $shelterInfo->delete();

        return response()->json([
            'message' => 'Інформацію про притулок успішно видалено'
        ]);
    }
}
