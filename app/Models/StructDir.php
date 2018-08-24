<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructDir extends Model
{
    protected $table = 'structdir';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [ 'empnik' => 'integer', 'dirnik' => 'integer', ];

}
