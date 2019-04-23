<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SapMasterData;
use App\Traits\HasUserEmployeeRelationship;
use Carbon\Carbon;

class PersonalData extends Model
{
	use SapMasterData, HasUserEmployeeRelationship;

	protected $table = 'it0002';
	protected $primaryKey = 'ID';
	public $incrementing = false;
	public $timestamps = false;

    public function getGbdatAttribute($value)
	{
		return Carbon::parse($value)->format('d M Y');
	}

	public function getFamdtAttribute($value)
	{
		return Carbon::parse($value)->format('d M Y');
	}
}
