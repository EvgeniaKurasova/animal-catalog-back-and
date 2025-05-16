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
        'animalID',
        'userID',
        'first_name',
        'last_name',
        'phone',
        'email',
        'message',
        'city',
        'is_processed',
        'is_archived',
        'comment',
    ];

    // Поля, які мають бути приховані при серіалізації
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Зв'язок з твариною
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animalID', 'animalID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
