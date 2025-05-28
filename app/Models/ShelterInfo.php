<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShelterInfo extends Model
{
    use HasFactory;

    // Назва таблиці в базі даних
    protected $table = 'shelter_info';

    // Первинний ключ
    protected $primaryKey = 'shelterID';

    // Поля, які можна масово заповнювати
    protected $fillable = [
        'logo',
        'main_photo',
        'name',
        'name_en',
        'phone',
        'email',
        'description',
        'description_en',
        'facebook',
        'instagram',
        'rule_id'
    ];

    // Поля, які мають бути приховані при серіалізації
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Зв'язок з правилами усиновлення
    public function rules()
    {
        return $this->belongsTo(AdoptionRule::class, 'rule_id', 'rule_id');
    }
}
