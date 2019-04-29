<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class It002 extends Model
{
    protected $table = 'it0002';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['PERNR' => 'integer'];
}
