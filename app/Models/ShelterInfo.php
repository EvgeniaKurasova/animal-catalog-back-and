<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShelterInfo extends Model
{
    use HasFactory;

    // Назва таблиці в базі даних
    protected $table = 'shelter_info';

    // Поля, які можна масово заповнювати
    protected $fillable = [
        'logo',
        'name',
        'name_en',
        'description',
        'description_en'
    ];

    // Поля, які мають бути приховані при серіалізації
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
