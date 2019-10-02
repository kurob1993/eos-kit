<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\SAP\Zhrom0007;


class Preferdis extends Model
{
    protected $fillable = ['preferdis_periode_id','begda','enda','sobid','otype','seark','rsign','relat','sclas','status','stext'];

    protected $appends = ['golongan'];

    public function getGolonganAttribute()
    {
        $golongan = Zhrom0007::where('AbbrPosition', $this->attributes['seark'])->get();
        return $golongan;
    }

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'sobid', 'personnel_no');
    }

    public function PreferdisPeriode()
    {
        return $this->belongsTo('App\Models\PreferdisPeriode');
    }

    public function zhrom0007()
    {
        return $this->belongsTo('App\Models\SAP\Zhrom0007','seark','AbbrPosition');
    }

    public function CompanyPosisition()
    {
        return $this->belongsTo('App\Models\CompanyPosisition','seark','AbbrPosition');
    }

    public function scopeOfLoggedUser($query)
    {
        $query->where('sobid', Auth::user()->personnel_no);
    }

    public function getProfileNameAttribute()
    {
        if($this->relat == '042')
        {
            return 'Preference';
        }
        else if($this->relat == '043')
        {
            return 'Dislikes';
        }
    }

    public function getStatusNameAttribute()
    {
        if($this->status == '0')
        {
            return 'Open';
        }
        else if($this->status == '1')
        {
            return 'Close';
        }
    }

}
