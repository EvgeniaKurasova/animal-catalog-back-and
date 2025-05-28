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
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'message',
        'city',
        'is_processed',
        'is_archived',
        'comment',
        'animal_name',
        'is_viewed',
        'created_at'
    
    ];

    // Якщо потрібно приховати тільки updated_at:
    protected $hidden = [
        'updated_at'
    ];

    protected $casts = [
        'is_viewed' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Зв'язок з твариною
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'animal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
