<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Models\AbsenceQuota;
use Session;

class LeaveFormComposer
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function compose(View $view)
    {
        try {
            // mendapatkan data employee dari user
            // dan mengecek apakah dapat melakukan pelimpahan
            $canDelegate = $this->user->employee()->firstOrFail()->hasSubordinate();

            // menyimpan global variable view ini
            $view->with('can_delegate', $canDelegate);

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return view('leaves.index');
        }

        try {
             // mendapatkan absence quota berdasarkan user
            $absenceQuota = AbsenceQuota::activeAbsenceQuota($this->user->personnel_no)
            ->with('absenceType:id,text')->firstOrFail();
  
            // menyimpan global variable view ini
            $view->with('absence_quota', $absenceQuota->toArray());

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Belum ada kuota cuti untuk periode saat ini. " . 
                    "Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect('/leaves');
        }
    }
}
