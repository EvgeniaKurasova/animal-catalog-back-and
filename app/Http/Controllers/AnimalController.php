<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{
    // Отримати список всіх тварин
    public function index(Request $request)
    {
        $query = Animal::query();

        // Фільтрація за видом тварини
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Фільтрація за статтю
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Фільтрація за віком (три категорії)
        if ($request->has('age_category')) {
            switch ($request->age_category) {
                case 'under_year':
                    $query->where('age', '<', 52); // до 1 року (52 тижні)
                    break;
                case 'one_to_five':
                    $query->whereBetween('age', [52, 260]); // 1-5 років (52-260 тижнів)
                    break;
                case 'over_five':
                    $query->where('age', '>', 260); // більше 5 років
                    break;
            }
        }

        // Фільтрація за розміром
        if ($request->has('size')) {
            $query->where('size', $request->size);
        }

        // Фільтрація за містом
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Сортування (за замовчуванням за датою створення)
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $animals = $query->with('shelter')->get();
        return response()->json($animals);
    }

    // Додати нову тварину
    public function store(AnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        if ($request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('animals', 'public');
                $paths[] = $path;
            }
            $animal->photos = $paths;
            $animal->save();
        }

        return $animal->load('shelter');
    }

    // Отримати інформацію про конкретну тварину
    public function show(Animal $animal)
    {
        return response()->json($animal->load('shelter'));
    }

    // Оновити інформацію про тварину
    public function update(AnimalRequest $request, Animal $animal)
    {
        $animal->update($request->validated());

        if ($request->hasFile('photos')) {
            // Видаляємо старі фото
            if ($animal->photos) {
                foreach ($animal->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            // Зберігаємо нові фото
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('animals', 'public');
                $paths[] = $path;
            }
            $animal->photos = $paths;
            $animal->save();
        }

        return response()->json($animal->load('shelter'));
    }

    // Видалити тварину
    public function destroy(Animal $animal)
    {
        // Видаляємо фото
        if ($animal->photos) {
            foreach ($animal->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $animal->delete();
        return response()->json(null, 204);
    }
}
