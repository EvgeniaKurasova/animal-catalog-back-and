<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\AnimalPhoto;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Storage;

class AnimalPhotoController extends Controller
{
    /**
     * Завантаження фото для тварини
     */
    public function upload(Request $request, $animalID)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $animal = Animal::findOrFail($animalID);

        $uploadedPhotos = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('animal_photos', 'public');

                // Додаємо запис в БД
                $animalPhoto = AnimalPhoto::create([
                    'animalID' => $animal->animalID,
                    'photo_path' => $path,
                    'is_main' => false // За замовчуванням фото не є головним
                ]);

                $uploadedPhotos[] = $animalPhoto;
            }
        }

        LoggingService::logError('Animal photos uploaded', [
            'animalID' => $animalID,
            'photos_count' => count($uploadedPhotos)
        ]);

        return response()->json(['message' => 'Photos uploaded successfully', 'photos' => $uploadedPhotos]);
    }

    /**
     * Встановлення головного фото
     */
    public function setMainPhoto($photoID)
    {
        $photo = AnimalPhoto::findOrFail($photoID);
        
        // Скидаємо всі фото цієї тварини як не головні
        AnimalPhoto::where('animalID', $photo->animalID)
            ->update(['is_main' => false]);
        
        // Встановлюємо нове головне фото
        $photo->update(['is_main' => true]);

        LoggingService::logError('Main photo set', [
            'photoID' => $photoID,
            'animalID' => $photo->animalID
        ]);

        return response()->json(['message' => 'Main photo updated successfully']);
    }

    /**
     * Видалення фото
     */
    public function delete($photoID)
    {
        $photo = AnimalPhoto::findOrFail($photoID);

        // Перевіряємо, чи існує файл перед видаленням
        if (Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        LoggingService::logError('Animal photo deleted', [
            'photoID' => $photoID,
            'animalID' => $photo->animalID
        ]);

        // Видаляємо з БД
        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    }
}

