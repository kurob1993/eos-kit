<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class StructDispSap extends Model
{
    protected $table = 'structdisp_sap';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function structDisp()
    {
        return $this->hasOne('\App\Models\SAP\StructDisp', 'empnik', 'empnik');
    }

    public function zhrom0007()
    {
        return $this->hasOne('\App\Models\SAP\Zhrom0007', 'AbbrPosition', 'emp_hrp1000_s_short');
    }
}
