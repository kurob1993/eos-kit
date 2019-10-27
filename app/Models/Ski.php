<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStage;
use App\Traits\OfLoggedUser;
use App\Models\Employee;
use App\Models\Secretary;

class Ski extends Model
{
    use ReceiveStage,OfLoggedUser;

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
    } 

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }

    public function skiApproval()
    {
        // one-to-many relationship dengan SkiApproval
        return $this->hasMany('App\Models\SkiApproval');
    }

    public function skiDetail()
    {
        // one-to-many relationship dengan SkiDetail
        return $this->hasMany('App\Models\SkiDetail');
    }

    public function secretary()
    {
        return $this->belongsTo('App\Models\Secretary');
    }
    
    public function getPlainIdAttribute()
    {
        return 'ski-' . $this->id;
    }
    public function getCreatedByAttribute()
    {
        if($this->dirnik !== null){
            $dirnik = Employee::find($this->dirnik);
        }else{
            $dirnik = Secretary::find($this->secretary_id);
        }
        
        return $dirnik->name;
    }
    public function getFirstApprovalAttribute()
    {
        // mencari approval dengan sequence 1
        return $this->skiApproval()->where('sequence', 1)->first();
    }

    public function getSecondApprovalAttribute()
    {
        // mencari approval dengan sequence 2
        return $this->skiApproval()->where('sequence', 2)->first();
    }
}
