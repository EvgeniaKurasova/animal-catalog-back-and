<?php

namespace App\Http\Controllers;

use App\Models\ShelterInfo;
use App\Http\Requests\ShelterInfoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ShelterInfoController extends Controller
{
    /**
     * Отримати інформацію про притулок
     */
    public function show(): JsonResponse
    {
        $shelterInfo = ShelterInfo::first();
        
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
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gmail' => 'required|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'rulesID' => 'required|exists:adoption_rules,ruleID',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->validated();

        // Обробка логотипу
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shelter-logos', 'public');
            $data['logo'] = $path;
        }

        // Обробка головного фото
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('shelter-main-photos', 'public');
            $data['main_photo'] = $path;
        }

        $shelterInfo = ShelterInfo::create($data);

        return response()->json($shelterInfo, Response::HTTP_CREATED);
    }

    /**
     * Оновити інформацію про притулок
     */
    public function update(ShelterInfoRequest $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gmail' => 'required|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'rulesID' => 'required|exists:adoption_rules,ruleID',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $shelterInfo = ShelterInfo::first();
        
        if (!$shelterInfo) {
            $shelterInfo = new ShelterInfo();
        }

        $data = $request->validated();

        // Обробка логотипу
        if ($request->hasFile('logo')) {
            if ($shelterInfo->logo && Storage::disk('public')->exists($shelterInfo->logo)) {
                Storage::disk('public')->delete($shelterInfo->logo);
            }
            $data['logo'] = $request->file('logo')->store('shelter-logos', 'public');
        }

        // Обробка головного фото
        if ($request->hasFile('main_photo')) {
            if ($shelterInfo->main_photo && Storage::disk('public')->exists($shelterInfo->main_photo)) {
                Storage::disk('public')->delete($shelterInfo->main_photo);
            }
            $data['main_photo'] = $request->file('main_photo')->store('shelter-main-photos', 'public');
        }

        $shelterInfo->fill($data);
        $shelterInfo->save();

        return response()->json($shelterInfo);
    }

    /**
     * Видалити інформацію про притулок
     */
    public function destroy(ShelterInfo $shelterInfo)
    {
        if ($shelterInfo->logo && Storage::disk('public')->exists($shelterInfo->logo)) {
            Storage::disk('public')->delete($shelterInfo->logo);
        }

        if ($shelterInfo->main_photo && Storage::disk('public')->exists($shelterInfo->main_photo)) {
            Storage::disk('public')->delete($shelterInfo->main_photo);
        }

        $shelterInfo->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
