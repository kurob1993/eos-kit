<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructDisp extends Model
{
    protected $table = 'structdisp';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $casts = [ 'empnik' => 'integer', 'dirnik' => 'integer', ];
    
    public function user()
    {
      // many-to-one relationship
      return $this->belongsTo('\App\User', 'personnel_no', 'empnik');
    }

    public function employee()
    {
      // many-to-one relationship
      return $this->belongsTo('\App\Models\Employee', 'personnel_no', 'empnik');
    }

    public function scopeStructOf($query, $p)
    {
      // struct untuk personnel_no
      return $query->where('empnik', $p);
    }

    public function scopeSelfStruct($query)
    {
      // struct untuk personnel_no
      return $query->where('no', 1);
    }

    public function scopeBossesOf($query, $p)
    {
      // struct atasan-atasan
      return $query->structOf($p)->where('no', '<>', 1);
    }
  
    public function scopeClosestBossOf($query, $p)
    {
      // struct mencari atasan satu tingkat diaatas  
      return $query->structOf($p)->where('no', '2');
    }

    public function scopeSubordinatesOf($query, $p)
    {
      // struct mencari bawahan-bawahan
      return $query->where('dirnik', $p)->where('no', '<>', '1');
    }
    
    public function scopeSubgroupStructOf($query, $s)
    {
      // struct mencari berdasarkan subgroup
      return $query
        ->where('emppersk', 'LIKE', strtoupper($s))
        ->where('no', 1);
    }
}
