<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\AnimalPhoto;
use Illuminate\Support\Facades\Storage;

class AnimalPhotoController extends Controller
{
    /**
     * Завантаження фото для тварини
     */
    public function upload(Request $request, $animal_id)
    {
        $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Перевіряємо, що це зображення
        ]);

        $animal = Animal::findOrFail($animal_id);

        $uploadedPhotos = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('animal_photos', 'public'); // Зберігаємо фото

                // Додаємо запис в БД
                $animalPhoto = AnimalPhoto::create([
                    'animal_id' => $animal->id,
                    'photo_path' => $path
                ]);

                $uploadedPhotos[] = $animalPhoto;
            }
        }

        return response()->json(['message' => 'Photos uploaded successfully', 'photos' => $uploadedPhotos]);
    }

    /**
     * Видалення фото
     */
    public function delete($id)
    {
        $photo = AnimalPhoto::findOrFail($id);

        // Видаляємо фото з диска
        Storage::disk('public')->delete($photo->photo_path);

        // Видаляємо з БД
        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    }
}

