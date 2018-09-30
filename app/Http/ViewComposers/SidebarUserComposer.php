<?php

namespace App\http\ViewComposers;

use Storage;
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

            if (Storage::disk('public')->exists($employee['personnel_no'] . '.jpg'))
                $view->with('picture', Storage::url( $employee['personnel_no'] . '.jpg' ));
            else
                $view->with('picture', Storage::url( 'default.png' ));

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan."
            ]);
            // batalkan view create dan kembali ke parent
            // return url('/leaves');
        }
    }
}
