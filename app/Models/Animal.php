<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals'; // Вказуємо назву таблиці

    protected $fillable = [
        'name',
        'name_en',
        'gender',
        'gender_en',
        'age',
        'size',
        'size_en',
        'city',
        'city_en',
        'description',
        'description_en',
    ]; // Поля, які можна масово заповнювати

    public function photos()
    {
        return $this->hasMany(AnimalPhoto::class, 'animal_id'); // Зв’язок з фото
    }
}
