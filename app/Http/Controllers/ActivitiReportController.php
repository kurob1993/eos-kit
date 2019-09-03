<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;

use App\Models\ActivitiReport;

class ActivitiReportController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        $nik = Auth::User()->personnel_no;
        $activity = ActivitiReport::where('Pernr', $nik);

        if (isset($request->search['value'])) {
            $cari = explode('|', $request->search['value']);
            $month = $cari[0];
            $year = $cari[1];

            if ($month) {
                $activity->whereMonth('tanggal', $month);
            }

            if ($year) {
                $activity->whereYear('tanggal', $year);
            }
        }

        // response untuk datatables absences
        if ($request->ajax()) {
            return Datatables::of($activity)
                ->editColumn('tanggal', function ($activity) {
                    $tanggal = date('d.m.Y', strtotime($activity->tanggal));
                    return $tanggal;
                })
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => false,
            'ordering' => true,
            'sDom' => 'tpi',
            'responsive' => true,
            'autoWidth' => false,
            'pageLength' => 50,
            "columnDefs" => [
                ["width" => "20%", "targets" => 0],
                ["width" => "20%", "targets" => 3],
                ["width" => "20%", "targets" => 4],
                ["width" => "20%", "targets" => 5],
            ]
        ]);

        $html = $htmlBuilder
            ->addColumn([
                'data' => 'tanggal',
                'name' => 'tanggal',
                'title' => 'TANGGAL',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'hari',
                'name' => 'hari',
                'title' => 'HARI',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'tprog',
                'name' => 'tprog',
                'title' => 'RULE',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'sobeg_soend',
                'name' => 'sobeg_soend',
                'title' => 'RENCANA JAM KERJA',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'itime_otime',
                'name' => 'itime_otime',
                'title' => 'AKTUAL JAM KERJA',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'beguz_enduz',
                'name' => 'beguz_enduz',
                'title' => 'RENCANA JAM LEMBUR',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'late',
                'name' => 'late',
                'title' => 'LAMBAT / CEPAT',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'wtact',
                'name' => 'wtact',
                'title' => 'J_KER AKTUAL',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'otact',
                'name' => 'otact',
                'title' => 'LEMBUR AKTUAL',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'otaut',
                'name' => 'otaut',
                'title' => 'LEMBUR AUTO',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'othit',
                'name' => 'othit',
                'title' => 'LEMBUR HITUNG',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'ijin',
                'name' => 'ijin',
                'title' => 'IZIN',
                'class' => 'none',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'cuti',
                'name' => 'cuti',
                'title' => 'CUTI',
                'class' => 'none',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'lain',
                'name' => 'lain',
                'title' => 'LAIN',
                'class' => 'none',
                'searchable' => false,
            ])
            ->addColumn([
                'data' => 'keterangan',
                'name' => 'keterangan',
                'title' => 'KETERANGAN',
                'class' => 'none',
                'searchable' => false,
            ]);

        $data = [
            'monthList' => ActivitiReport::where('Pernr', $nik)->monthList()->get(),
            'yearList' => ActivitiReport::where('Pernr', $nik)->yearList()->get(),
        ];
        return view('activity.index')->with(compact('html', 'activity', 'data'));
    }

    public function list($personnel_no = null)
    {
        $nik = $personnel_no;
        if ($nik) {
            try {
                //code...
                $a = scandir( config('emss.activity.dir') );
            } catch (\Throwable $th) {
                //throw $th;
                $a = [];
            }
            
            $matches  = preg_grep('/.*_' . $nik . '_.*/i', $a);
            $res = array();
            foreach ($matches as $key => $value) {
                array_push(
                    $res,
                    array(
                        'id' => $key,
                        'text' => $value,
                        'value' => $value,
                    )
                );
            }
            return json_encode(
                array('results' => $res)
            );
        } else {
            return json_encode(
                array(
                    'results' =>
                    array(
                        'id' => 0,
                        'text' => 'tidak ada data',
                        'value' => null,
                    )
                )
            );
        }
    }

    public function download(Request $request, $file = null)
    {
        if ($file) {
            $readdir = '/nfs/interface/LapActivity/';
            $movedir = '/nfs/interface/LapActivity/Archive/';
            $arfile  = scandir($readdir);

            $data = explode('_', $file);
            $nik = explode('.', $data[1]);
            $date = explode('.', $data[2]);
            $tgl = $date[0];
            $bulan = date('m', strtotime($tgl));
            $tahun = date('Y', strtotime($tgl));

            foreach ($arfile as $key => $value) {
                if ($value == $file) {
                    //delete data
                    $delete = ActivitiReport::where('Pernr',$nik)
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun)
                        ->delete();

                    $file_handle = fopen($readdir . $value, "rb");
                    $i = 1;
                    while (!feof($file_handle)) {
                        $line_of_text = fgets($file_handle);
                        if (strlen($line_of_text) > 0) {
                            $this->tulisfile($line_of_text);
                        }
                        $i++;
                    }
                    fclose($file_handle);
                }
            }

            copy($readdir . $file, $movedir . $file);
            unlink($readdir . $file);
        }
    }

    public function tulisfile($teks)
    {
        $delimiteriactiviti_report = "|";
        $splitcontentsactiviti_report = explode($delimiteriactiviti_report, $teks);

        try {
            $simpan = new ActivitiReport();
            $simpan->Pernr = rtrim($splitcontentsactiviti_report[0]);
            $simpan->tanggal = rtrim($splitcontentsactiviti_report[1]);
            $simpan->hari = rtrim($splitcontentsactiviti_report[2]);
            $simpan->tprog = rtrim($splitcontentsactiviti_report[3]);
            $simpan->sobeg_soend = rtrim($splitcontentsactiviti_report[4]);
            $simpan->itime_otime = rtrim($splitcontentsactiviti_report[5]);
            $simpan->beguz_enduz = rtrim($splitcontentsactiviti_report[6]);
            $simpan->late = rtrim($splitcontentsactiviti_report[7]);
            $simpan->wtact = rtrim($splitcontentsactiviti_report[8]);
            $simpan->otact = rtrim($splitcontentsactiviti_report[9]);
            $simpan->otaut = rtrim($splitcontentsactiviti_report[10]);
            $simpan->othit = rtrim($splitcontentsactiviti_report[11]);
            $simpan->ijin = rtrim($splitcontentsactiviti_report[12]);
            $simpan->cuti = rtrim($splitcontentsactiviti_report[13]);
            $simpan->lain = rtrim($splitcontentsactiviti_report[14]);
            $simpan->keterangan = rtrim($splitcontentsactiviti_report[15]);
            $simpan->update_at = date('Y-m-d h:i:s');
            $simpan->save();
        } catch (\Throwable $th) {
            return array(["response" => "gagal"]);
        }
    }
}
