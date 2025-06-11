<?php

namespace App\Http\Controllers;

use Illuminate\Database\Seeder;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\LoggingService;
use App\Services\CacheService;
use App\Models\AnimalPhoto; // Додано для роботи з фото


class AnimalController extends Controller
{
    // Отримати список всіх тварин
    public function index(Request $request)
    {
        $query = Animal::query();

        // Фільтрація за видом тварини
        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
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
        if ($request->has('size_id')) {
            $query->where('size_id', $request->size_id);
        }

        // Фільтрація за містом
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Сортування (за замовчуванням за датою створення)
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $animals = $query->with('photos')->get();


        // Додаємо поточний вік до кожної тварини
        $animals = $animals->map(function ($animal) {
            $animal->current_age = $animal->getCurrentAge();
            return $animal;
        });

        // Завжди повертаємо масив (навіть якщо порожній)
        return response()->json([
            'data' => $animals->values(),
            'meta' => [
                'total' => $animals->count(),
                'filters' => $request->all()
            ]
        ]);
    }

    // Додати нову тварину
    public function store(AnimalRequest $request)
    {

        LoggingService::logError('123');
        $data = $request->validated();
        $data['age_updated_at'] = Carbon::now();

        $animal = Animal::create($data);

        // Завантаження фото
        if ($request->hasFile('photos')) {
            $request->validate([
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif'
            ]);

            $photosData = $request->input('photos_data', []); // масив із is_main
            $files = $request->file('photos');

            $mainSet = false;

            foreach ($files as $index => $photo) {
                $path = $photo->store('animals', 'public');

                $isMain = false;
                if (isset($photosData[$index]['is_main'])) {
                    $isMain = filter_var($photosData[$index]['is_main'], FILTER_VALIDATE_BOOLEAN);
                }

                if ($isMain) $mainSet = true;

                AnimalPhoto::create([
                    'animal_id' => $animal->animal_id,
                    'photo_path' => $path,
                    'is_main' => $isMain,
                ]);
            }

            // Якщо не було вибрано головного фото — призначити перше
            if (!$mainSet && count($files) > 0) {
                $firstPhoto = AnimalPhoto::where('animal_id', $animal->animal_id)->first();
                if ($firstPhoto) {
                    $firstPhoto->is_main = true;
                    $firstPhoto->save();
                }
            }
        }

        $animal->current_age = $animal->getCurrentAge();

        LoggingService::logAnimalAction('created', $animal->toArray());
        CacheService::clearAnimalCache();

        return response()->json([
            'data' => $animal->load('photos', 'mainPhoto'),
            'message' => 'Animal created successfully'
        ], 201);
    }

    // Отримати інформацію про конкретну тварину
    public function show(Animal $animal)
    {
        // $animal->load('shelter');
        // $currentAge = $animal->getCurrentAge();
        // $animal->current_age = $currentAge;
        
        // return response()->json($animal);
        $animal->load('photos', 'mainPhoto');
        $animal->current_age = $animal->getCurrentAge();

        return response()->json([
            'data' => $animal
        ]);
    }

    // Оновити інформацію про тварину
    public function update(AnimalRequest $request, Animal $animal)
    {
        $data = $request->validated();
        $data['age_updated_at'] = Carbon::now();

        $animal->update($data);

        if ($request->hasFile('photos')) {
            $request->validate([
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif'
            ]);

            $photosData = $request->input('photos_data', []);
            $files = $request->file('photos');
            $mainSet = $animal->photos()->where('is_main', true)->exists();

            foreach ($files as $index => $photo) {
                $path = $photo->store('animals', 'public');

                $isMain = false;
                if (isset($photosData[$index]['is_main'])) {
                    $isMain = filter_var($photosData[$index]['is_main'], FILTER_VALIDATE_BOOLEAN);
                }

                if ($isMain) $mainSet = true;

                AnimalPhoto::create([
                    'animal_id' => $animal->animal_id,
                    'photo_path' => $path,
                    'is_main' => $isMain,
                ]);
            }

            if (!$mainSet && $animal->photos()->count() > 0) {
                $firstPhoto = $animal->photos()->first();
                $firstPhoto->is_main = true;
                $firstPhoto->save();
            }
        }

        $animal->current_age = $animal->getCurrentAge();

        LoggingService::logAnimalAction('updated', $animal->toArray());
        CacheService::clearAnimalCache();

        return response()->json([
            'data' => $animal->fresh()->load('photos', 'mainPhoto'),
            'message' => 'Animal updated successfully'
        ]);
    }


    // Видалити тварину
    public function destroy(Animal $animal)
    {
        foreach ($animal->photos as $photo) {
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }
            $photo->delete();
        }

        $animal->delete();

        LoggingService::logAnimalAction('deleted', $animal->toArray());
        CacheService::clearAnimalCache();

        return response()->json([
            'message' => 'Animal deleted successfully'
        ], 204);
    }

}
