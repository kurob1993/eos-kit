<?php

namespace App\Models;
use App\Traits\OfLoggedUser;
use Illuminate\Database\Eloquent\Model;


class Director extends Model
{
    //
    use OfLoggedUser;
    protected $table = 'structdireksi';
    public $timestamps = false;
    protected $primaryKey = 'empnik';
    protected $casts = [
        'empnik' => 'binary',
    ];

    public $fillable = [
        'no',
        'empnik',
        'empname',
        'empposid',
        'emp_hrp1000_s_short',
        'emppostx',
        'emportx',
        'emppersk',
        'LSTUPDT',
        'ttl'
    ];

    public function scopefoundDirectors($query){
        $query->selectRaw('empname')
        ->selectRaw('empnik')
        ->selectRaw('emppostx')
        ->selectRaw('emportx')
        ->where("emppostx","like","DIR%");
    }

    public function scopefoundCommisary($query){
        $query->selectRaw('empname')
        ->selectRaw('empnik')
        ->selectRaw('emppostx')
        ->where("emppostx","like","KOM%");
    }

    public function scopefoundID($query, $m){
        return $query->whereRaw('empnik = ' . $m);
    }


}
