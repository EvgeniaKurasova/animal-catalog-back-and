<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AnimalPhoto::class);
    }

    public function mainPhoto(): HasOne
    {
        return $this->hasOne(AnimalPhoto::class)->where('is_main', true);
    }
}
