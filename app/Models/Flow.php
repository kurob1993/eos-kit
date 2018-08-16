<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{

  public $fillable = [
      'description'
  ];

  protected $casts = [
      'id' => 'integer',
      'description' => 'string'
  ];

  public static $rules = [];

  public function stages()
  {
    return $this->belongsToMany('App\Models\Stage')->withPivot('sequence');
  }
}
