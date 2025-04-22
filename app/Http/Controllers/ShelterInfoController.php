<?php

namespace App\Http\Controllers;

use App\Models\ShelterInfo;
use App\Http\Requests\ShelterInfoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ShelterInfoController extends Controller
{
    /**
     * Отримати інформацію про притулок
     */
    public function index()
    {
        $shelterInfo = ShelterInfo::first();
        
        if (!$shelterInfo) {
            return response()->json([
                'message' => 'Інформація про притулок не знайдена'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($shelterInfo);
    }

    /**
     * Створити інформацію про притулок
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|max:2048',
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string'
        ]);

        $shelterInfo = new ShelterInfo($request->except('logo'));

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shelter', 'public');
            $shelterInfo->logo = $path;
        }

        $shelterInfo->save();

        return response()->json($shelterInfo, Response::HTTP_CREATED);
    }

    /**
     * Оновити інформацію про притулок
     */
    public function update(ShelterInfoRequest $request, ShelterInfo $shelterInfo)
    {
        $data = $request->validated();

        // Обробка логотипу
        if ($request->hasFile('logo')) {
            // Видаляємо старий логотип, якщо він існує
            if ($shelterInfo->logo) {
                Storage::delete($shelterInfo->logo);
            }
            
            // Зберігаємо новий логотип
            $path = $request->file('logo')->store('shelter-logos');
            $data['logo'] = $path;
        }

        $shelterInfo->update($data);
        return response()->json($shelterInfo);
    }

    /**
     * Видалити інформацію про притулок
     */
    public function destroy(ShelterInfo $shelterInfo)
    {
        // Видаляємо логотип, якщо він існує
        if ($shelterInfo->logo) {
            Storage::delete($shelterInfo->logo);
        }

        $shelterInfo->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
