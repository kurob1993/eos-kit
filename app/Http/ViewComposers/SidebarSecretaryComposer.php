<?php

namespace App\http\ViewComposers;

use Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarSecretaryComposer
{
    public function compose(View $view)
    {
        try {
            // mendapatkan data secretary dari user
            $secretary = Auth::user();
            
            // menyimpan global variable view ini
            $view->with('secretary', $secretary->toArray());

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan."
            ]);
        }
    }
}
