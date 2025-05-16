<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionRule extends Model
{
    protected $table = 'adoption_rules';

    protected $fillable = [
        'rules',
        'rules_en',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
