<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;

class Admin  extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'gender',
        'email',
        'password',
        'phone',
        'user_profile',
        'role_id',
    ];

    public $timestamps = false;

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
