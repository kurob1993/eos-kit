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

    public function attendanceQuota()
    {
        return $this->hasMany('App\Models\AttendanceQuota');
    }

    public function hasSecretaryRole()
    {
        $secretaryRole = $this->roles->filter(function ($item, $key) {
            return $item->name == 'secretary';
        });

        return ($secretaryRole->count() <> 1) ? false : true;
    }
}