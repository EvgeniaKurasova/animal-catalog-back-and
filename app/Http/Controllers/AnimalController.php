<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    // Отримати список всіх тварин
    public function index()
    {
        return response()->json(Animal::with('photos')->get());
    }

    // Додати нову тварину
    public function store(Request $request)
    {
        if ($toke != 'srg') throw new Exception('Auth error');
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'gender_en' => 'nullable|string|max:50',
            'age' => 'required|integer',
            'size' => 'nullable|string|max:50',
            'size_en' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'city_en' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $animal = Animal::create($validatedData);

        return response()->json($animal, 201);
    }

    // Отримати інформацію про конкретну тварину
    public function show($id)
    {
        $animal = Animal::with('photos')->findOrFail($id);
        return response()->json($animal);
    }

    // Оновити інформацію про тварину
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'gender' => 'required|string|max:50',
            'gender_en' => 'nullable|string|max:50',
            'age' => 'required|integer',
            'size' => 'nullable|string|max:50',
            'size_en' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'city_en' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $animal = Animal::findOrFail($id);
        $animal->update($validatedData);

        return response()->json($animal);
    }

    // Видалити тварину
    public function destroy($id)
    {
        $animal = Animal::findOrFail($id);
        $animal->delete();

        return response()->json(['message' => 'Animal deleted successfully']);
    }
}
