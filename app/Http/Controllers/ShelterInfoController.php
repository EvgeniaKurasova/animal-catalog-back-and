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
    public function store(ShelterInfoRequest $request)
    {
        $data = $request->validated();
        $shelterInfo = ShelterInfo::create($data);
        return response()->json([
            'message' => 'Інформацію про притулок успішно створено',
            'data' => $shelterInfo
        ], 201);
    }

    /**
     * Оновити інформацію про притулок
     */
    public function update(ShelterInfoRequest $request, ShelterInfo $shelterInfo)
    {
        $data = $request->validated();
        $shelterInfo->update($data);
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
