<?php

namespace App\Http\Controllers;

use App\Models\ShelterInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Оновити інформацію про притулок
     */
    public function update(Request $request)
    {
        $shelterInfo = ShelterInfo::first();

        if (!$shelterInfo) {
            $shelterInfo = new ShelterInfo();
        }

        $validated = $request->validate([
            'logo' => 'nullable|string',
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string'
        ]);

        $shelterInfo->fill($validated);
        $shelterInfo->save();

        return response()->json($shelterInfo);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
