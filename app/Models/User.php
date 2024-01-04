<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'email', 
        'username', 
        'password', 
        'fullname', 
        'role_id', 
        'avatar'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}