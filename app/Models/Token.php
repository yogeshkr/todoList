<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_in'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
