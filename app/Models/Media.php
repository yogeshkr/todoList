<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * @property $id
 * @property $username
 * @property $email
 * @property $password
 * @property $api_key
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 */
class Media extends Model
{
    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'todo_id',
        'media_file'
    ];


    /**
     * Get todo of Media
     *
     * @property $todo
     */
    public function todo()
    {
        return $this->belongsTo(Todo::class, 'todo_id');
    }
}
