<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals'; // Вказуємо назву таблиці

    protected $fillable = [
        'name',
        'name_en',
        'type',
        'type_en',
        'gender',
        'age_years',
        'age_months',
        'size',
        'size_en',
        'age_updated_at',
        'additional_information',
        'additional_information_en',
        'shelterID',
    ]; // Поля, які можна масово заповнювати

    protected $casts = [
        'age_updated_at' => 'datetime',
    ];

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(ShelterInfo::class, 'shelterID', 'shelterID');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AnimalPhoto::class);
    }

    public function mainPhoto(): HasOne
    {
        return $this->hasOne(AnimalPhoto::class)->where('is_main', true);
    }

    /**
     * Отримати поточний вік тварини
     * @return array{age_years: int, age_months: int}
     */
    public function getCurrentAge(): array
    {
        $now = Carbon::now();
        $ageUpdatedAt = $this->age_updated_at ?? $this->created_at;
        $monthsPassed = $now->diffInMonths($ageUpdatedAt);

        $totalMonths = $this->age_months + $monthsPassed;
        $years = $this->age_years + floor($totalMonths / 12);
        $months = $totalMonths % 12;

        return [
            'age_years' => (int)$years,
            'age_months' => (int)$months
        ];
    }
}
