<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AbsenceApproval;
use App\Models\AttendanceApproval;
use App\Models\Transition;


class HomeController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // user yang sedang login
            $this->user = Auth::user()->employee;

            return $next($request);
        });
    }

    public function index()
    {
        if ($this->user->isManager() || $this->user->isGeneralManager()) {
            return redirect()
                ->route('dashboard.index');
        } else {
            return redirect()
                ->route('cvs.index');
        }
    }

    public function noRole()
    {
        return view('errors.403');
    }

    public function storeToDelegation($module,$id)
    {
        switch ($module) {
            case 'leave':
                $approved = AbsenceApproval::find($id)->absence;

                $start_date = $approved->start_date->toDateString();
                $end_date = $approved->end_date->toDateString();
                $strucdisp = $approved->employee->StructDisp->first();

                $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                ->where('start_date',$start_date)
                ->where('end_date',$end_date);
            break;
            case 'absence':
                $approved = AbsenceApproval::find($id)->absence;

                $start_date = $approved->start_date->toDateString();
                $end_date = $approved->end_date->toDateString();
                $strucdisp = $approved->employee->StructDisp->first();

                $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                ->where('start_date',$start_date)
                ->where('end_date',$end_date);
            break;
            case 'attendance':
                $approved = AttendanceApproval::find($id)->attendance;

                $start_date = $approved->start_date->toDateString();
                $end_date = $approved->end_date->toDateString();
                $strucdisp = $approved->employee->StructDisp->first();

                $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                ->where('start_date',$start_date)
                ->where('end_date',$end_date);
            break;
        }

        if(!$transition->update(['actived_at'=>date('Y-m-d H:i:s')])){
            // tampilkan pesan bahwa telah berhasil menyetujui
            Session::flash("flash_notification", [
                "level" => "warning",
                "message" => "Gagal approve dikaarnakan data delegasi tidak dapat di simpan."
            ]);

            return redirect()->back();
        }
    }
}