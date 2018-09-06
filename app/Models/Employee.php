<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'personnel_no';
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        // one-to-one relationship dengann User
        return $this->belongsTo('App\User', 'personnel_no');
    }
    
    public function structDisp()
    {
      // one-to-many relationship dengan StructDisp
      return $this->hasMany('App\Models\StructDisp', 'empnik', 'personnel_no');
    }

    public function canDelegate()
    {
        // apakah boleh melakukan pelimpahan wewenang?
        return (($this->esgrp == 'BS') || ($this->esgrp == 'AS'))
        ? true : false;
    }

    public function allowedForOvertime()
    {
        // apakah boleh melakukan lembur?
        return ( ($this->esgrp == 'ES') || ($this->esgrp == 'EF') 
                 || ($this->esgrp == 'F') )
        ? true : false;
    }

    public function director()
    {
        // mencari record structdisp untuk employee ini
        $s = $this->structDisp()->selfStruct()->first();

        // substr pada angka paling depan
        $oShort = substr($s->emp_hrp1000_o_short, 0, 1);
        $sShort = substr($s->emp_hrp1000_s_short, 0, 1);

        // mengembalikan StructDir untuk employee ini
        return \App\Models\StructDir::whereRaw('SUBSTR(emp_hrp1000_s_short, 1, 1) = ?', [$oShort])
            ->whereRaw('SUBSTR(emp_hrp1000_o_short, 1, 1) = ?', [$sShort])
            ->first();
    }

    public function subordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = \App\Models\StructDisp::subordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return \App\Models\Employee::where('personnel_no', $item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function closestBoss()
    {
        // mencari atasan satu tingkat
        $s = $this->structDisp()->closestBossOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s)) ? [] : (\App\Models\Employee::where('personnel_no', $s->dirnik)->first());
    }

    public function bosses()
    {
        // mencari semua atasan
        $structs = $this->structDisp()->bossesOf($this->personnel_no)->get();

        // mengiterasi atasan-atasan dan membuat collection baru
        $bosses = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing atasan
            return \App\Models\Employee::where('personnel_no', $item->dirnik)->first();;
        });

        // mengembalikan collection of Employee
        return $bosses;
    }

    public function superintendentBoss()
    {
        // mencari semua atasan
        $s = $this->structDisp()->superintendentOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s)) ? [] : (\App\Models\Employee::where('personnel_no', $s->dirnik)->first());
    }    

    public function managerBoss()
    {
        // mencari semua atasan
        $s = $this->structDisp()->managerOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s)) ? [] : (\App\Models\Employee::where('personnel_no', $s->dirnik)->first());
    }    

    public function generalManagerBoss()
    {
        // mencari semua atasan
        $s = $this->structDisp()->generalManagerOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s)) ? [] : (\App\Models\Employee::where('personnel_no', $s->dirnik)->first());
    }

    public function minSuperintendentBoss()
    {
        // mencari atasan dengan minimal level CS
        // apabila tidak ditemukan maka cari di level BS
        // apabila tidak ditemukan di level BS
        // maka cari di level AS    
       $superintendent = $this->superintendentBoss();
        
        if (!$superintendent)
            return $this->minManagerBoss($this->personnel_no);
        else
            return $superintendent;
    }

    public function minManagerBoss()
    {
        // meneruskan recursive call dari atas
        $manager = $this->managerBoss();
        
        if (!$manager)
            return $this->generalManagerBoss($this->personnel_no);
        else
            return $manager;
    }
}