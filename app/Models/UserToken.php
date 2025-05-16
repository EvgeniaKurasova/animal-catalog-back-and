<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';

    protected $fillable = [
        'userID',
        'token',
        'expiredAt',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
