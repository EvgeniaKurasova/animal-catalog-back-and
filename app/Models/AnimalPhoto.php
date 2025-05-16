<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimalPhoto extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'animalID',
        'photo_path',
        'is_main',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'animalID', 'animalID');
    }
}
