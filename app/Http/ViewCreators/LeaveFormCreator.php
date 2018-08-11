<?php

namespace App\http\ViewCreators;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Models\AbsenceQuota;
use Session;

class LeaveFormCreator
{
    private $user;
    private $absenceQuota;
    private $canDelegate;

    public function __construct()
    {
        $this->user = Auth::user();

        try {
            $this->canDelegate = $this->user->employee()->firstOrFail()->hasSubordinate();                

        } catch (ModelNotFoundException $e) {
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
           $this->absenceQuota = AbsenceQuota::activeAbsenceQuota($this->user->personnel_no)
               ->with('absenceType:id,text')->firstOrFail();

       } catch(ModelNotFoundException $e) {
           // tampilkan pesan bahwa tidak ada absence quota
           Session::flash("flash_notification", [
               "level"=>"danger",
               "message"=>"Belum ada kuota cuti untuk periode saat ini. " . 
                   "Silahkan hubungi Divisi HCI&A."
           ]);
           // batalkan view create dan kembali ke parent
           return view('leaves.index');
       }
    }

    public function create(View $view)
    {
            // menyimpan global variable view ini
            $view->with('can_delegate', $this->canDelegate);
            // menyimpan global variable view ini
            if (!is_null($this->absenceQuota))
                $view->with('absence_quota', $this->absenceQuota->toArray());
                
    }
}
