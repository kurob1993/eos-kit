<?php

namespace App\Models;

use Eloquent as Model;

class AbsenceType extends Model
{
    public $fillable = [ 'subtype', 'text' ];
    protected $casts = [ 'id' => 'integer', 'subtype' => 'string', 'text' => 'string' ];

    public static $rules = [ ];

    public function absences()
    {
        // one-to-many relationship dengan Absence
        return $this->hasMany(\App\Models\Absence::class);
    }
}
