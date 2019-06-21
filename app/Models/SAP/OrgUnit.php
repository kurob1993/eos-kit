<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class OrgUnit extends Model
{
    protected $table = 'orgunit';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function orgText()
    {
        return $this->hasOne('App\Models\SAP\OrgText','ObjectID','ObjectID');
    }

    public function scopeAtas($query)
    {
        $query->where('S', 'A');
    }

    public function scopeOrg($query)
    {
        $query->where('RO', 'O');
    }

    public function scopeCurrentPeriod($query)
    {
        $query->where('EndDate', '9999-12-31');
    }

    public function scopeGivenPeriod($query, $p)
    {
        $query->where('EndDate', '>=', $p)
            ->where('Startdate', '<=', $p);
    }

    public function scopeFindByObjectID($query, $o)
    {
        $query->where('ObjectID', $o);
    }

    public function scopeFindObjectIDByPeriod($query, $o, $p)
    {
        $query->findByObjectID($o)
            ->givenPeriod($p);
    }

    public function scopeFindObjectIDByCurrent($query, $o)
    {
        $query->findByObjectID($o)
            ->currentPeriod();
    }

    public function getParentAttribute() 
    {
        return OrgUnit::findObjectIDByCurrent($this->IDrelatedobject)->first();
    }
}
