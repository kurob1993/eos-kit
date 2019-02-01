<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgText extends Model
{
    protected $table = 'org_text';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function scopeLastOrg($query)
    {
        $query->where('EndDate', '9999-12-31');
    }

    public function scopeFindByCompositeKey($query, $o, $d)
    {
        $query->findByObjectID($o)
            ->where('EndDate', '>', $d)
            ->where('Startdate', '<', $d);
    }

    public function scopeFindByObjectID($query, $o)
    {
        $query->where('ObjectID', $o);
    }

    public function scopeFindByObjectabbr($query, $o)
    {
        $query->where('Objectabbr', $o);
    }

    public function scopeLastUk($query, $o, $d)
    {
        $query->where('Objectname', 'like', $o.'%');

        if (is_null($d)) {
            $query->where('EndDate', '9999-12-31');
        } else {
            $query->where('EndDate', '>', $d)
                ->where('Startdate', '<', $d)->first();
        }

    }

    public function scopeOldDiv($query, $o)
    {
        $query->where('Objectname', 'like', $o.'%')
              ->where('EndDate','<>', '9999-12-31');
    }
}
