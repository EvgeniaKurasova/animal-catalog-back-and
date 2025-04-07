<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalPhoto extends Model
{
    use HasFactory;

    protected $table = 'animal_photos'; // Вказуємо назву таблиці

    protected $fillable = ['animal_id', 'photo_path']; // Поля, які можна заповнювати

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id'); // Зв’язок з твариною
    }
}
