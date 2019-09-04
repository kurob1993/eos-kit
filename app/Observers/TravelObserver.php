<?php

namespace App\Observers;

use Session;
use App\models\Travel;

class TravelObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(Travel $travel)
    {
        // apakah tanggal Travel sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED(5) dan CANCELLED(6) tidak termasuk intersected
        $intersected = Travel::where('personnel_no', $travel->personnel_no)
            ->whereNotIn('stage_id', [5,6])
            ->intersectWith($travel->start_date, $travel->end_date)
            ->first();
            
        if ($intersected) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat melakukan pengajuan pada tanggal tersebut "
                . "karena sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->formattedPeriod . ").",
            ]);
            return false;
        }
    }

    public function created(Travel $travel)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(Travel $travel)
    {
        //
    }
}