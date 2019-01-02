<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class Secretary extends Authenticatable
{
    use LaratrustUserTrait;

    protected $guard = 'secr';
    protected $fillable = ['name', 'email', 'boss', 'password'];
    protected $hidden = ['password', 'remember_token'];
}