<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SAP\StructDisp;
use App\Models\SAP\StructDir;
use App\User;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\AttendanceQuota;
use App\Models\TimeEvent;
use App\Models\SAP\Zhrom0013;
use App\Models\SAP\StructJab;
use App\Models\SAP\StructDispSap;

class Employee extends Model
{
    protected $primaryKey = 'personnel_no';
    public $incrementing = false;
    public $timestamps = false;
    protected $appends = ['empposid','emp_hrp1000_s_short','old_abbr'];

    public function getOldAbbrAttribute()
    {
        $s = StructDispSap::where('no','1')
            ->where('empnik',$this->personnel_no)
            ->first();
        return $s['emp_hrp1000_s_short'];
    }

    public function getEmpHrp1000SShortAttribute()
    {
        $s = StructDisp::select('emp_hrp1000_s_short')
            ->where('no','1')
            ->where('empnik',$this->personnel_no)
            ->first();
        return $s['emp_hrp1000_s_short'];
    }

    public function getEmpposidAttribute()
    {
        $s = StructDisp::select('empposid')
            ->where('no','1')
            ->where('empnik',$this->personnel_no)
            ->first();
        return $s['empposid'];
    }

    public function skis()
    {
        // one-to-many relationship dengan ski
        return $this->hasMany('App\Models\Ski', 'personnel_no', 'personnel_no');
    }

    public function user()
    {
        // one-to-one relationship dengann User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function attendanceQuotas()
    {
        // one-to-many relationship dengan AttendanceQuota
        return $this->hasMany('App\Models\AttendanceQuota', 'personnel_no', 'personnel_no');
    }

    public function attendances()
    {
        // one-to-many relationship dengan Attendance
        return $this->hasMany('App\Models\Attendance', 'personnel_no', 'personnel_no');
    }

    public function absences()
    {
        // one-to-many relationship dengan Absence
        return $this->hasMany('App\Models\Absence', 'personnel_no', 'personnel_no');
    }

    public function currentPeriodAbsences()
    {
        return $this->absences()
            ->currentPeriod();
    }

    public function nonLeaveAbsences()
    {
        // one-to-many relationship dengan Absence exclude leave
        return $this->hasMany('App\Models\Absence', 'personnel_no', 'personnel_no')
        ->excludeLeaves();
    }

    public function currentPeriodNonLeaveAbsences()
    {
        return $this->nonLeaveAbsences()
            ->currentPeriod();
    }

    public function leaves()
    {
        // one-to-many relationship dengan Absence leaves only
        return $this->hasMany('App\Models\Absence', 'personnel_no', 'personnel_no')
        ->leavesOnly();
    }

    public function currentPeriodLeaves()
    {
        return $this->leaves()
            ->currentPeriod();
    }

    public function timeEvents()
    {
        // one-to-many relationship dengan Time Event
        return $this->hasMany('App\Models\TimeEvent', 'personnel_no', 'personnel_no');
    }

    public function currentPeriodTimeEvents()
    {
        return $this->timeEvents()->whereMonth('check_date', date('m'))
        ->whereYear('check_date', date('Y'));
    }

    public function absenceQuotas()
    {
        // one-to-many relationship dengan AbsenceQuota
        return $this->hasMany('App\Models\AbsenceQuota', 'personnel_no', 'personnel_no');
    }

    public function getActiveAbsenceQuotaAttribute()
    {
        $now = Carbon::now()->toDateTimeString();

        return $this->absenceQuotas()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
    }

    public function absenceApprovals()
    {
        // one-to-many relationship dengan AbsenceApproval
        return $this->hasMany('App\Models\AbsenceApproval', 'regno', 'personnel_no');
    }

    public function attendanceApprovals()
    {
        // one-to-many relationship dengan AttendanceApproval
        return $this->hasMany('App\Models\AttendanceApproval', 'regno', 'personnel_no');
    }

    public function skiApprovals()
    {
        // one-to-many relationship dengan AttendanceApproval
        return $this->hasMany('App\Models\SkiApproval', 'regno', 'personnel_no');
    }

    public function structDisp()
    {
        // one-to-many relationship dengan StructDisp
        return $this->hasMany('App\Models\SAP\StructDisp', 'empnik', 'personnel_no');
    }

    public function families()
    {
        // one-to-many relationship dengan it0021
        return $this->hasMany('App\Models\SAP\Family', 'PERNR', 'personnel_no');
    }

    public function positions()
    {
        // one-to-many relationship dengan it0001
        return $this->hasMany('App\Models\SAP\Position', 'PERNR', 'personnel_no');
    }

    public function scopeFindByPersonnel($query, $p)
    {
        // mencari data StructDisp pada diri sendiri (no == 1)
        $struct = StructDisp::structOf($p)
            ->selfStruct()
            ->first();

        // jika ditemukan datanya di StructDisp
        if (!is_null($struct)) {

            // mencari di Employee
            $employee = $query
                ->where('personnel_no', $p)
                ->first();

            // jika tidak ditemukan di Employee buat baru
            if (is_null($employee)) {
                $employee = new Employee();
                $employee->personnel_no = $struct->empnik;
            }

            // buat baru / update perubahan employee
            $employee->name = $struct->empname;
            $employee->esgrp = $struct->emppersk;
            $employee->cost_ctr = $struct->empkostl;
            $employee->position_name = $struct->emppostx;
            $employee->org_unit_name = $struct->emportx;
            $employee->save();

            // jika tidak ada record user maka buatkan
            $user = User::where('personnel_no', $p)->first();
            if (is_null($user)) {
                $user = new User();
                $user->personnel_no = $employee->personnel_no;
                $user->name = $employee->name;
                $user->email = Hash::make(str_random());
                $user->password = Hash::make(str_random());
                $user->save();
            }
        }

        // kembalikan data Employee berdasarkan pencarian (terbaru)
        return $query
            ->where('personnel_no', $p);
    }

    public function scopeFindByCostCenter($query, $c)
    {
        // mencari data StructDisp pada diri sendiri (no == 1)
        $struct = SAP\StructDisp::selfStruct()
            ->costCenterOf($c)
            ->get();

        $struct->each(function ($item, $key) {

            // mencari di Employee
            $employee = Employee::where('personnel_no', $item->empnik)
                ->first();

            // jika tidak ditemukan di Employee buat baru
            if (is_null($employee)) {
                $employee = new Employee();
                $employee->personnel_no = $item->empnik;
            }

            // buat baru / update perubahan employee
            $employee->name = $item->empname;
            $employee->esgrp = $item->emppersk;
            $employee->cost_ctr = $item->empkostl;
            $employee->position_name = $item->emppostx;
            $employee->org_unit_name = $item->emportx;
            $employee->save();
        });

        // kembalikan data Employee berdasarkan pencarian (terbaru)
        return $query->where('cost_ctr', $c);
    }

    public function isSuperintendent()
    {
        return (substr($this->esgrp, 0, 1) == 'C') ? true : false;
    }

    public function isManager()
    {
        return (substr($this->esgrp, 0, 1) == 'B') ? true : false;
    }

    public function isGeneralManager()
    {
        return (substr($this->esgrp, 0, 1) == 'A') ? true : false;
    }

    public function isStructuralGeneralManager()
    {
        return ($this->esgrp == 'AS') ? true : false;
    }

    public function isStructuralManager()
    {
        return ($this->esgrp == 'BS') ? true : false;
    }

    public function canDelegate()
    {
        // apakah boleh melakukan pelimpahan wewenang?
        return (($this->esgrp == 'CS') || ($this->esgrp == 'BS') || ($this->esgrp == 'AS'))
        ? true : false;
    }

    public function allowedForOvertime()
    {
        // apakah boleh melakukan lembur?
        return (($this->esgrp == 'ES') || ($this->esgrp == 'EF')
                 || ($this->esgrp == 'F'))
        ? true : false;
    }

    public function allowedToSubmitSubordinateOvertime()
    {
        // apakah boleh mengajukan lembur untuk bawahan?
        return (($this->esgrp != 'ES') && ($this->esgrp != 'EF')
            && ($this->esgrp != 'F'))
        ? true : false;
    }

    public function director()
    {
        // mencari record structdisp untuk employee ini
        $s = $this->structDisp()->selfStruct()->first();

        // substr pada angka paling depan
        $oShort = substr($s->emp_hrp1000_o_short, 0, 1);
        $sShort = substr($s->emp_hrp1000_s_short, 0, 1);

        // mengembalikan StructDir untuk employee ini
        return StructDir::whereRaw('SUBSTR(emp_hrp1000_s_short, 1, 1) = ?', [$oShort])
            ->whereRaw('SUBSTR(emp_hrp1000_o_short, 1, 1) = ?', [$sShort])
            ->first();
    }

    public function closestSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::closestSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function closestStructuralSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::closestSubordinatesOf($this->personnel_no, 'structural')->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function oneTwoDirectSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::oneTwoDirectSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function subordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::subordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function foremanAndOperatorSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::foremanAndOperatorSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function mgrSptSpvSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::mgrSptSpvSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function gmMgrSptSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::gmMgrSptSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function gmMgrSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::gmMgrSubordinatesOf($this->personnel_no)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function superintendentSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::superintendentSubordinatesOf($this->personnel_no, true)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function managerSubordinates()
    {
        // mencari semua bawahan-bawahan
        $structs = StructDisp::managerSubordinatesOf($this->personnel_no, true)->get();

        // mengiterasi bawahan-bawahan dan membuat collection baru
        $subordinates = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing bawahan
            return Employee::findByPersonnel($item->empnik)->first();
        });

        // mengembalikan collection of Employee
        return $subordinates;
    }

    public function bosses()
    {
        // mencari semua atasan
        $structs = $this->structDisp()->bossesOf($this->personnel_no)->get();

        // mengiterasi atasan-atasan dan membuat collection baru
        $bosses = $structs->map(function ($item, $key) {
            // membuat & mengembalikan Employee masing-masing atasan
            return Employee::findByPersonnel($item->dirnik)->first();
        });

        // mengembalikan collection of Employee
        return $bosses;
    }

    public function closestBoss()
    {
        // menginisialisasi ulang atasan
        $this->bosses();

        // mencari atasan satu tingkat
        $s = $this->structDisp()->closestBossOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s)) ? [] : (Employee::findByPersonnel($s->dirnik)->first());
    }

    public function superintendentBoss()
    {
        // menginisialisasi ulang atasan
        $this->bosses();

        // mencari semua atasan
        $s = $this->structDisp()->superintendentOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s) || $this->isSuperintendent()) ?
            [] :
            (Employee::findByPersonnel($s->dirnik)->first());
    }

    public function managerBoss()
    {
        // menginisialisasi ulang atasan
        $this->bosses();

        // mencari atasan struktural manager & atasan fungsional manager
        $s = $this->structDisp()->managerOf($this->personnel_no)->first();
        $sf = $this->structDisp()->functionalManagerOf($this->personnel_no)->first();
        $m = (is_null($s)) ? $sf : $s;

        // mengembalikan Employee model
        if (is_null($m)) {
            return [];
        } else {
            return Employee::findByPersonnel($m->dirnik)->first();
        }
    }

    public function generalManagerBoss()
    {
        // menginisialisasi ulang atasan
        $this->bosses();

        // mencari semua atasan
        $s = $this->structDisp()->generalManagerOf($this->personnel_no)->first();

        // mengembalikan Employee model
        return (is_null($s) || $this->isGeneralManager()) ?
            [] :
            (Employee::findByPersonnel($s->dirnik)->first());
    }

    public function minSuperintendentBoss()
    {
        // mencari atasan dengan minimal level CS
        // apabila tidak ditemukan maka cari di level BS
        // apabila tidak ditemukan di level BS
        // maka cari di level AS

        if ($this->isSuperintendent() || $this->isManager()) {
            return $this->closestBoss();
        } else {
            $superintendent = $this->superintendentBoss();

            if (!$superintendent) {
                return $this->minManagerBoss();
            } else {
                return $superintendent;
            }
        }
    }

    public function minManagerBoss()
    {
        if ($this->isSuperintendent() || $this->isManager()) {
            return $this->closestBoss();
        } else {
            // meneruskan recursive call dari atas
            $manager = $this->managerBoss();

            if (!$manager) {
                return $this->generalManagerBoss();
            } else {
                return $manager;
            }
        }
    }

    public function getPersonnelNoWithNameAttribute()
    {
        // menggabungkan personnel_no dan nama
        return $this->personnel_no . ' - ' . $this->name;
    }

    public function getIsATransferKnowledgeAttribute()
    {
        $s = $this->structdisp()->selfStruct()->first();

        return $s->emp_hrp1000_s_short == '6200300001'
            && $s->emp_hrp1000_o_short == '62003';
    }

    public function getLeaveTotalDurationHourAttribute()
    {
        $leaves = Absence::where('personnel_no', $this->personnel_no)
            ->leavesOnly()
            ->currentPeriod()
            ->successOnly()
            ->get();

        return $leaves->sum(function ($leave) {
            return $leave->hourDuration;
        });
    }

    public function getPermitTotalDurationHourAttribute()
    {
        $absences = Absence::where('personnel_no', $this->personnel_no)
            ->excludeLeaves()
            ->currentYearPeriod()
            ->successOnly()
            ->get();

        $sum = $absences->sum(function ($absence) {
            return $absence->hourDuration;
        });

        $attendances = Attendance::where('personnel_no', $this->personnel_no)
            ->currentYearPeriod()
            ->successOnly()
            ->get();

        $sum += $attendances->sum(function ($attendance) {
            return $attendance->hourDuration;
        });

        return $sum;
    }

    public function getTimeEventTotalDurationAttribute()
    {
        $sum = 0;
        $timeEvents = TimeEvent::where('personnel_no', $this->personnel_no)
            ->currentYearPeriod()
            ->successOnly()
            ->get();

        $sum += $timeEvents->sum(function ($timeEvent) {
            return 1;
        });

        return $sum;
    }

    public function overtimeTotalDurationHour($month, $year)
    {
        $sum = 0;
        $overtimes = AttendanceQuota::monthYearPeriodOf($month, $year, $this->personnel_no)
            ->successOnly()
            ->get();

        $sum += $overtimes->sum(function ($overtime) {
            return $overtime->hourDuration;
        });

        return $sum / 60;
    }

    public function successOvertimeFoundMonth()
    {
        $o = AttendanceQuota::where('personnel_no', $this->personnel_no)
            ->successOnly()
            ->selectRaw('MONTH(start_date) as month')
            ->orderBy(DB::raw('MONTH(start_date)'), 'desc')
            ->groupBy(DB::raw('MONTH(start_date)'))
            ->get();
        
        return $o->map(function ($item, $key) {
            return $item->month;
        });
    }

    public function successOvertimeFoundYear()
    {
        $o = AttendanceQuota::where('personnel_no', $this->personnel_no)
            ->successOnly()
            ->selectRaw('YEAR(start_date) as year')
            ->orderBy(DB::raw('YEAR(start_date)'), 'desc')
            ->groupBy(DB::raw('YEAR(start_date)'))
            ->get();

        return $o->map(function ($item, $key) {
            return $item->year;
        });
    }

    public function getPermitsAttribute()
    {
        $absences = Absence::where('personnel_no', $this->personnel_no)
            ->excludeLeaves()
            ->get();

        return Attendance::where('personnel_no', $this->personnel_no)
            ->get()
            ->merge($absences);
    }

    public function getCurrentPeriodPermitsAttribute()
    {
        $absences = Absence::where('personnel_no', $this->personnel_no)
            ->excludeLeaves()
            ->currentPeriod()
            ->get();

        return Attendance::where('personnel_no', $this->personnel_no)
            ->currentPeriod()
            ->get()
            ->merge($absences);
    }

    public function getSuperintendentJobs($nojabatan = null)
    {
        //no jabatan karyawan
        if (is_null($nojabatan)) {
            $nojabatan = $this->structDisp->first()['emp_hrp1000_s_short'];
        }
        
        //no jabatan boss
        $directboss = StructJab::where('no', $nojabatan)
        ->orderBy('id', 'desc')
        ->first();
        $NoAtasan = $directboss['boss_no'];
        $GolAtasan = $directboss['boss_gol'];

        if ($GolAtasan > 'CS') {
            return $this->getSuperintendentJobs($NoAtasan);
        }

        if ($GolAtasan !== 'CS') {
            return ['job'=>null, 'bos'=>null];
        }

        $StructDisp = StructDisp::where('emp_hrp1000_s_short', $NoAtasan)->first();
        return ['job'=>$NoAtasan, 'bos'=>$StructDisp];
    }

    public function getManagerJobs($nojabatan = null)
    {
        //no jabatan karyawan
        if (is_null($nojabatan)) {
            $nojabatan = $this->structDisp->first()['emp_hrp1000_s_short'];
        }
        
        //no jabatan boss
        $directboss = StructJab::where('no', $nojabatan)
        ->orderBy('id', 'desc')
        ->first();
        $NoAtasan = $directboss['boss_no'];
        $GolAtasan = $directboss['boss_gol'];

        if ($GolAtasan > 'BS') {
            return $this->getManagerJobs($NoAtasan);
        }
        
        if ($GolAtasan !== 'BS') {
            return ['job'=>null, 'bos'=>null];
        }

        $StructDisp = StructDisp::where('emp_hrp1000_s_short', $NoAtasan)->first();
        return ['job'=>$NoAtasan, 'bos'=>$StructDisp];
    }

    public function getGmJobs($nojabatan = null)
    {
        //no jabatan karyawan
        if (is_null($nojabatan)) {
            $nojabatan = $this->structDisp->first()['emp_hrp1000_s_short'];
        }
        
        //no jabatan boss
        $directboss = StructJab::where('no', $nojabatan)
        ->orderBy('id', 'desc')
        ->first();
        $NoAtasan = $directboss['boss_no'];
        $GolAtasan = $directboss['boss_gol'];
        
        if ($GolAtasan > 'AS') {
            return $this->getGmJobs($NoAtasan);
        }
        
        if ($GolAtasan !== 'AS') {
            return ['job'=>null, 'bos'=>null];
        }

        $StructDisp = StructDisp::where('emp_hrp1000_s_short', $NoAtasan)->first();
        return ['job'=>$NoAtasan, 'bos'=>$StructDisp];
    }

    public function sptBossWithDelegation()
    {
        //get boss
        $jobsBoss = $this->getSuperintendentJobs();

        //tanggal sekarang
        $now = date('Y-m-d');

        //cek data pengalihan
        $transition = Transition::where('abbr_jobs', $jobsBoss['job'])
        ->where(function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
        })->where('actived_at', '<>', null);

        if ($transition->count()) {
            $delegation = $transition->first();
            return Employee::where('personnel_no', $delegation->personnel_no)->first();
        } else {
            if ($jobsBoss['bos']) {
                return Employee::where('personnel_no', $jobsBoss['bos']['empnik'])->first();
            } else {
                return [];
            }
        }
    }

    public function managerBossWithDelegation()
    {
        //get boss
        $jobsBoss = $this->getManagerJobs();

        //tanggal sekarang
        $now = date('Y-m-d');

        //cek data pengalihan
        $transition = Transition::where('abbr_jobs', $jobsBoss['job'])
        ->where(function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
        })->where('actived_at', '<>', null);
        
        if ($transition->count()) {
            $delegation = $transition->first();
            return Employee::where('personnel_no', $delegation->personnel_no)->first();
        } else {
            if ($jobsBoss['bos']) {
                return Employee::where('personnel_no', $jobsBoss['bos']['empnik'])->first();
            } else {
                return [];
            }
        }
    }

    public function gmBossWithDelegation()
    {
        //get boss
        $jobsBoss = $this->getGmJobs();

        //tanggal sekarang
        $now = date('Y-m-d');

        //cek data pengalihan
        $transition = Transition::where('abbr_jobs', $jobsBoss['job'])
        ->where(function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
        })->where('actived_at', '<>', null);
        
        if ($transition->count()) {
            $delegation = $transition->first();
            return Employee::where('personnel_no', $delegation->personnel_no)->first();
        } else {
            if ($jobsBoss['bos']) {
                return Employee::where('personnel_no', $jobsBoss['bos']['empnik'])->first();
            } else {
                return $this->generalManagerBoss();
            }
        }
    }

    /**
     * Menampilkan atasan minimal supperintenden
     * dengan pe;impahan
     *
     * 1. Jika data adalah supperintenden atau manager
     *    tampilkan atasan langsung
     * 2. jika level atasan langsung tidak sama
     *    dengan C (Bukan supperintendent)
     *    lempar ke minManagerBossWithDelegation
     * 3. jika data superintendent null
     *          maka cari data superintendent di pelimpahan
     *    jika tidak
     *          tampilkan data superintendent
     * 4. jika data superintendent di pelimpahan null
     *          maka tampilkan null
     */
    public function minSptBossWithDelegation()
    {
        $superintendent = $this->sptBossWithDelegation();
        if (!$superintendent) {
            return $this->minManagerBossWithDelegation();
        } else {
            return $superintendent;
        }
    }

    public function minManagerBossWithDelegation()
    {
        // meneruskan recursive call dari atas
        $manager = $this->managerBossWithDelegation();
        if (!$manager) {
            return $this->gmBossWithDelegation();
        } else {
            return $manager;
        }
    }
}
