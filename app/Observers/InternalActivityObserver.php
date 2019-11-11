<?php

namespace App\Observers;

use App\Models\Activity;
use Session;

class InternalActivityObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\Activity  $Activity
     * @return void
     */
    public function created(Activity $activity)
    {
        // tampilkan pesan bahwa telah menyimpan activiti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" =>  "Berhasil Menyimpan ".ucfirst($activity->type)." Activity",
        ]);
    }

    public function updated(Activity $activity)
    {
        if($activity->stage_id == 2){
            $m = [
                "level" => "success",
                "message" => ucfirst($activity->type)." Activity Approved"
            ];
        }

        if($activity->stage_id == 5){
            $m = [
                "level" => "danger",
                "message" => ucfirst($activity->type)." Activity Denied"
            ];
        }

        // tampilkan pesan bahwa telah menyimpan activiti
        Session::flash("flash_notification", $m);
    }
}
