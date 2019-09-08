<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Preferdis;
use App\Models\PreferdisPeriode;

class PreferdisPeriodeController extends Controller
{
    //

    public function index(Request $request, Builder $htmlBuilder)
    {
        $preferdisPeriodes = PreferdisPeriode::all();

        // response untuk datatables preferdis
        if ($request->ajax()) {
            return Datatables::of($preferdisPeriodes)
                ->addIndexColumn()
                ->editColumn('tanggalmulai', function ($periode) {
                    // periode
                    return  '<span class="label label-primary">'.$periode->start_date.'</span>';
                })
                ->editColumn('tanggalakhir', function ($periode) {
                    return  '<span class="label label-warning">'.$periode->finish_date.'</span>';
                })
                ->editColumn('action', function ($periode) {
                    $views = '';
                        $delete =  view('preferdis_periodes._delete', [
                            'id' => $periode->id
                        ]);

                        $edit = view('preferdis_periodes._edit', [
                            'id' => $periode->id
                        ]);

                    $views = $delete.''.$edit;
                        
                    return $views ;
                })
                
                // ->setRowAttr([
                //     // href untuk dipasang di setiap tr
                //     'data-href' => function ($preferece) {
                //         return route('preference.show', ['leaf' => $preferece->id]);
                //     } 
                // ])
                ->escapeColumns([0,1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
            'ordering'=> true,
            'sDom' => 'tpi',
            'responsive' => [ 'details' => true ],
        ]);

        $html = $htmlBuilder
        ->addColumn([
            'data' => 'DT_Row_Index',
            'name' => 'DT_Row_Index',
            'title' => 'No',
            'searchable' => false,
            'orderable' => false, 
        ])
        ->addColumn([
            'data' => 'tanggalmulai',
            'name' => 'tanggalmulai',
            'title' => 'Tanggal Mulai',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'tanggalakhir',
            'name' => 'tanggalakhir',
            'title' => 'Tanggal Akhir',
            'searchable' => true,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'searchable' => false,
            'orderable' => false,
        ]);

        //dd($html);
        // tampilkan view index dengan tambahan script html DataTables
        return view('preferdis_periodes.index')->with(compact('html'));
    }

    public function destroy($id)
    {
        try {
            // tampilkan pesan bahwa telah berhasil diinput
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Berhasil menghapus data periode.",
            ]);

            // hapus data
            $preferdis = PreferdisPeriode::find($id);
            $preferdis->delete();
            return redirect()->route('preferdis.periode.index');

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa ada kegagalan system
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal manghapus data, silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periode.index');
        }
    }

    public function create(Request $request)
    {
        try {
            return view('preferdis_periodes.create');
        } catch(Exception $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periodes.index');
        }
    }
    

    public function store(Request $request)
    {
        try {
            $dateNow    =  $request->input('start_date');

            // tampilkan pesan bahwa telah berhasil diinput
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Berhasil membuat periode input data.",
            ]);

            // cek data periode
            $cek = PreferdisPeriode::where('finish_date', '>=', $dateNow)
                ->where('start_date', '<=', $dateNow)
                ->get()
                ->count();

            // dd($cek);
            
            if($cek <= 0)
            {   
                // insert to table preferdis_periode
                $preferdisPeriode = PreferdisPeriode::create([
                    'start_date' => $request->input('start_date'),
                    'finish_date' => $request->input('end_date'),
                    'status'=> '1'
                ]);

                return redirect()->route('preferdis.periode.index');
            }
            else 
            {
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>"Gagal meng-input data, Periode dengan rentang tanggal tersebut sudah ada."
                ]);

                return redirect()->route('preferdis.periode.create');
            }


        } catch(Exception $e) {
            // exception apabila terjadi error
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal meng-input data, silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periode.create');
        }
    }

    public function edit($id)
    {
        try {
            // mengambil data periode berdasarkan id
            $periode = PreferdisPeriode::find($id);

            // pisahkan start_date dan finish_date 
            $startDate = Carbon::parse($periode->start_date);
            $finishDate = Carbon::parse($periode->finish_date);
            
            $string_startDate = $startDate->format('Y-m-d');
            $string_finishDate = $finishDate->format('Y-m-d');
            // mencari selisih hari
            $jumlahHari = $startDate->diffInDays($finishDate) + 1;

            // batalkan view create dan kembali ke parent
            return view('preferdis_periodes.edit', compact('string_startDate', 'string_finishDate', 'jumlahHari', 'periode'));

        } catch(Exception $e) {
             // exception apabila terjadi error
             Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal meng-input data, silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periode.create');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // tampilkan pesan bahwa telah berhasil diinput
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Periode berhasil diupdate.",
            ]);

            PreferdisPeriode::where('id', $id)
                ->update([
                    'start_date' => $request->input('start_date'),
                    'finish_date' => $request->input('end_date'),
            ]);

            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periode.index');

        } catch(Exception $e) {
            // exception apabila terjadi error
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal meng-input data, silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferdis.periode.edit', $id);
        }
    }
}
