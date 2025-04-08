<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdoptionRequest extends Model
{
    use HasFactory;

    // Назва таблиці в базі даних
    protected $table = 'adoption_requests';

    // Поля, які можна масово заповнювати
    protected $fillable = [
        'animal_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'city',
        'message',
        'is_processed'
    ];

    // Поля, які мають бути приховані при серіалізації
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Зв'язок з твариною
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
}
