<?php

namespace App\Observers;

use Session;
use App\Models\Transition;

class TransitionObserver
{
    // before
    public function creating(Transition $transition)
    {
        $abbr_jobs = $transition->abbr_jobs;

       //tanggal sekarang
        $now = date('Y-m-d');

        //cek data pengalihan
        $transition = Transition::where('abbr_jobs', $abbr_jobs)
        ->where(function($query) use ($now){
            $query->where('start_date','<=',$now)
            ->where('end_date','>=',$now);
        });

        if($transition->count() == 0){
            return true;
        }

        Session::flash("flash_notification", [
            "level" => "danger",
            "message" => "Data pengalihan dengan 
                No Jabatan : ".$transition->first()->abbr_jobs." 
                tersebut masih aktif",
        ]);
        return false;
    }

    //after
    public function created(Transition $transition)
    {
        
    }
}
