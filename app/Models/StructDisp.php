<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructDisp extends Model
{
    protected $table = 'structdisp';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['empnik' => 'integer', 'dirnik' => 'integer'];

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

    public function scopeGmMgrSpt($query)
    {
        return $query->whereIn('emppersk', ['AS,', 'AF', 'BS', 'BF', 'CS', 'CF']);
    }

    public function scopeGmMgr($query)
    {
        return $query->whereIn('emppersk', ['AS,', 'AF', 'BS', 'BF']);
    }

    public function scopeMgrSptSpv($query)
    {
        return $query->whereIn('emppersk', ['BS', 'BF', 'CS', 'CF', 'DS', 'DF']);
    }

    public function scopeForemanAndOperator($query)
    {
        return $query->whereIn('emppersk', ['ES', 'EF', 'F']);
    }

    public function scopeSuperintendent($query, $emppersk = 'all')
    {
        if ($emppersk == 'structural')
            return $query->whereIn('emppersk', ['CS']);
        elseif ($emppersk == 'functional')
            return $query->whereIn('emppersk', ['CF']);
        else
            return $query->whereIn('emppersk', ['CS', 'CF']);
    }

    public function scopeManager($query, $emppersk = 'all')
    {
        if ($emppersk == 'structural')
            return $query->whereIn('emppersk', ['BS']);
        elseif ($emppersk == 'functional')
            return $query->whereIn('emppersk', ['BF']);
        else
            return $query->whereIn('emppersk', ['BS', 'BF']);
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

    /**************  Start of subordinates function **************/
    public function scopeTwoOnly($query)
    {
        $query->where('no', '2');
    }

    public function scopeTwoAndThreeOnly($query)
    {
        $query->where(function ($query){ $query->where('no', '2')->orWhere('no', '3'); });
    }

    public function scopeSubordinatesOf($query, $p)
    {
        // struct mencari bawahan-bawahan
        return $query->where('dirnik', $p)->where('no', '<>', '1');
    }

    public function scopeClosestSubordinatesOf($query, $p, $emppersk = 'all')
    {
        // struct mencari bawahan langsung yang struktural/fungsional/semua
        if ($emppersk == 'structural') {
            return $query->subordinatesOf($p)
                ->twoOnly()
                ->where('emppersk', 'LIKE', '%S');
        } elseif ($emppersk == 'functional') {
            return $query->subordinatesOf($p)
                ->twoOnly()
                ->where('emppersk', 'LIKE', '%F');
        } else {
            return $query->subordinatesOf($p)
                ->twoOnly();
        }
    }

    public function scopeOneTwoDirectSubordinatesOf($query, $p, $emppersk = 'all')
    {
        // struct mencari bawahan 1 & 2 tingkat dibawah yang struktural/fungsional/all
        if ($emppersk == 'structural') {
            return $query->subordinatesOf($p)
                ->twoAndThreeOnly()
                ->where('emppersk', 'LIKE', '%S');
        } elseif ($emppersk == 'functional') {
            return $query->subordinatesOf($p)
                ->twoAndThreeOnly()
                ->where('emppersk', 'LIKE', '%F');
        } else {
            return $query->subordinatesOf($p)
                ->where('no', '2');
        }        
    }

    public function scopeSuperintendentSubordinatesOf($query, $p, $emppersk = 'all')
    {
        return $query->subordinatesOf($p)->superintendent($emppersk);
    }

    public function scopeManagerSubordinatesOf($query, $p, $emppersk = 'all')
    {
        return $query->subordinatesOf($p)->manager($emppersk);
    }

    public function scopeForemanAndOperatorSubordinatesOf($query, $p)
    {
        return $query->subordinatesOf($p)->ForemanAndOperator();
    }

    public function scopeGmMgrSubordinatesOf($query, $p)
    {
        return $query->subordinatesSubordinatesOf($p)->gmMgr();
    }

    public function scopeGmMgrSptSubordinatesOf($query, $p)
    {
        return $query->subordinatesOf($p)->gmMgrSpt();
    }

    public function scopeMgrSptSpvSubordinatesOf($query, $p)
    {
        return $query->subordinatesOf($p)->mgrSptSpv();
    }
    /************** End of subordinate function ************* */

    /************** Start of boss function ************* */
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
    /************** End of boss function ************* */
}
