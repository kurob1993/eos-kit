<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public function scopeRetrieveAdmin($query)
    {
        // mencari user admin
        return $query->with('users')
            ->where('name', 'admin')
            ->first()
            ->users
            ->first();
    }
}
