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
        ->where(function($query) use ($transition){
            $query->where('start_date','<=',$transition->start_date)
            ->where('end_date','>=',$transition->start_date);
        })
        ->orWhere(function($query) use ($transition){
            $query->where('start_date','<=',$transition->end_date)
            ->where('end_date','>=',$transition->end_date);
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
