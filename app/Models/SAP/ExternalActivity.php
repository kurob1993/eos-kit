<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SapMasterData;
use App\Traits\HasUserEmployeeRelationship;

class ExternalActivity extends Model
{
    use SapMasterData, HasUserEmployeeRelationship;

    protected $table = 'it0219';
	protected $primaryKey = 'ID';
	public $incrementing = false;
	public $timestamps = false;    
}
