<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SapMasterData;
use App\Traits\HasUserEmployeeRelationship;
use Carbon\Carbon;

class Family extends Model
{
    use SapMasterData, HasUserEmployeeRelationship;

    protected $table = 'it0021';
    protected $primaryKey = 'ID';
    public $incrementing = false;
    public $timestamps = false;

    public function getFgbdtAttribute($value)
	{
		return Carbon::parse($value)->format('d M Y');
	}	    
}
