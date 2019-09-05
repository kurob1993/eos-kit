<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class Zhrom0007 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zhrom0007';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function structDispSap()
    {
        return $this->belongsTo('\App\Models\SAP\StructDispSap');
    }

    public function preferdis()
    {
        return $this->hasMany('\App\Models\Preferdis');
    }
}
