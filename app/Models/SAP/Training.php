<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SapMasterData;
use App\Traits\HasUserEmployeeRelationship;

class Training extends Model
{
    use SapMasterData, HasUserEmployeeRelationship;

    protected $table = 'train';
	protected $primaryKey = 'ID';
	public $incrementing = false;
	public $timestamps = false;
}
