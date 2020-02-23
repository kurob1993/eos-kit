<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructDisp extends Model
{
    protected $table = 'structdisp';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['empnik' => 'string', 'dirnik' => 'integer'];

    public function user()
    {
        // many-to-one relationship
        return $this->belongsTo('\App\User', 'personnel_no', 'empnik');
    }

    public function employee()
    {
        // many-to-one relationship
        return $this->belongsTo('\App\Models\Employee', 'empnik', 'personnel_no');
    }

    public function employeeBoss()
    {
        // many-to-one relationship
        return $this->belongsTo('\App\Models\Employee', 'dirnik', 'personnel_no');
    }

    public function scopeCostCenterOf($query, $c)
    {
        // struct untuk mencari by cost center
        return $query->where('empkostl', $c);
    }

    public function scopeFindByShortAbbrOrg($query, $abbr_org)
    {
        // struct untuk mencari by Abbreviasi Org
        return $query
            ->selfStruct()
            ->where('emp_hrp1000_o_short', $abbr_org);
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
        // struct mencari atasan satu tingkat di atas
        return $query->structOf($p)->where('no', '2');
    }

    public function scopeSubordinatesOf($query, $p)
    {
        // struct mencari bawahan-bawahan
        return $query->where('dirnik', $p)->where('no', '<>', '1');
    }

    public function scopeMgrSptSpv($query)
    {
        return $query->whereIn('emppersk', ['BS', 'BF', 'CS', 'CF', 'DS', 'DF']);
    }

    public function scopeForemanAndOperator($query)
    {
        return $query->whereIn('emppersk', ['ES', 'EF', 'F']);
    }

    public function scopeForemanAndOperatorSubordinatesOf($query, $p)
    {
        return $query->subordinatesOf($p)->ForemanAndOperator();
    }

    public function scopeMgrSptSpvOf($query, $p)
    {
        return $query->subordinatesOf($p)->mgrSptSpv();
    }

    public function scopeSubgroupStructOf($query, $s)
    {
        // struct mencari berdasarkan subgroup
        return $query
            ->where('emppersk', 'LIKE', strtoupper($s))
            ->where('no', 1);
    }

    public function scopeSuperintendentOf($query, $p)
    {
        // struct mencari atasan superintendent
        return $query
            ->structOf($p)
            ->whereHas('employeeBoss', function ($query) {
                $query->where('esgrp', 'CS');
            });
    }

    public function scopeManagerOf($query, $p)
    {
        // struct mencari atasan manager
        return $query
            ->structOf($p)
            ->whereHas('employeeBoss', function ($query) {
                $query->where('esgrp', 'BS');
            });
    }

    public function scopeFunctionalManagerOf($query, $p)
    {
        // struct mencari atasan manager
        return $query
            ->structOf($p)
            ->whereHas('employeeBoss', function ($query) {
                $query->where('esgrp', 'BF');
            });
    }

    public function scopeGeneralManagerOf($query, $p)
    {
        // struct mencari atasan general manager
        return $query
            ->structOf($p)
            ->whereHas('employeeBoss', function ($query) {
                $query->where('esgrp', 'AS');
            });
    }
}
