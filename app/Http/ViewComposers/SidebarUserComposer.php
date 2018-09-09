<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarUserComposer
{
    private $userRepository;

    public function compose(View $view)
    {
        try {
            // mendapatkan data employee dari user
            $employee = Auth::user()->employee()->firstOrFail();
            
            // menyimpan global variable view ini
            $view->with('employee', $employee->toArray());

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan."
            ]);
            // batalkan view create dan kembali ke parent
            // return urlu('/leaves');
        }
    }
}
