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

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Activity  $Activity
     * @return void
     */
    public function deleting(Activity $activity)
    {
        //
    }
}
