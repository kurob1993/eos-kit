<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;

class TravelApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval;
    protected $fillable = ['status_id'];

    public function travel()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Travel');
    } 
    
    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    } 
    
    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }

    public function scopeSelectTravel($query, $travel_id){
        return $query::where('travel_id',$travel_id);
    }
}
