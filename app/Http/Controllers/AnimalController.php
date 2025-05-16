<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

        // Фільтрація за віком
        if ($request->has('age_category')) {
            switch ($request->age_category) {
                case 'under_year':
                    $query->where(function($q) {
                        $q->where('age_years', 0)
                          ->where('age_months', '<', 12);
                    });
                    break;
                case 'one_to_five':
                    $query->where(function($q) {
                        $q->where(function($q) {
                            $q->where('age_years', 1)
                              ->where('age_months', '>=', 0);
                        })->orWhere(function($q) {
                            $q->where('age_years', '>', 1)
                              ->where('age_years', '<', 5);
                        });
                    });
                    break;
                case 'over_five':
                    $query->where('age_years', '>=', 5);
                    break;
            }
        } else {
            // Звичайна фільтрація за роками та місяцями
            if ($request->has('age_years')) {
                $query->where('age_years', $request->age_years);
            }
            if ($request->has('age_months')) {
                $query->where('age_months', $request->age_months);
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
        
        // Додаємо поточний вік до кожної тварини
        $animals->transform(function ($animal) {
            $currentAge = $animal->getCurrentAge();
            $animal->current_age = $currentAge;
            return $animal;
        });

        return response()->json($animals);
    }

    // Додати нову тварину
    public function store(AnimalRequest $request)
    {
        $data = $request->validated();
        $data['age_updated_at'] = Carbon::now();
        
        $animal = Animal::create($data);

        if ($request->hasFile('photos')) {
            $request->validate([
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('animals', 'public');
                $paths[] = $path;
            }
            $animal->photos = $paths;
            $animal->save();
        }

        $animal->load('shelter');
        $currentAge = $animal->getCurrentAge();
        $animal->current_age = $currentAge;
        
        return response()->json($animal);
    }

    // Отримати інформацію про конкретну тварину
    public function show(Animal $animal)
    {
        $animal->load('shelter');
        $currentAge = $animal->getCurrentAge();
        $animal->current_age = $currentAge;
        
        return response()->json($animal);
    }

    // Оновити інформацію про тварину
    public function update(AnimalRequest $request, Animal $animal)
    {
        $data = $request->validated();
        $data['age_updated_at'] = Carbon::now();
        
        $animal->update($data);

        if ($request->hasFile('photos')) {
            $request->validate([
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Видаляємо старі фото
            if ($animal->photos) {
                foreach ($animal->photos as $photo) {
                    if (Storage::disk('public')->exists($photo)) {
                        Storage::disk('public')->delete($photo);
                    }
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

        $animal->load('shelter');
        $currentAge = $animal->getCurrentAge();
        $animal->current_age = $currentAge;
        
        return response()->json($animal);
    }

    // Видалити тварину
    public function destroy(Animal $animal)
    {
        // Видаляємо фото
        if ($animal->photos) {
            foreach ($animal->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        $animal->delete();
        return response()->json(null, 204);
    }
}
