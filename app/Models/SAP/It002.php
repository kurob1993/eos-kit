<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class It002 extends Model
{
    protected $table = 'it0002';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['PERNR' => 'integer'];
}
