<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Slip;
use Carbon\Carbon;

class PayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->has('type_id') && $request->has('year_id')) {
            $data = Slip::offLoggedUserPay()
                ->orderBy('periode', 'desc')
                ->TypeYearof(
                    $request->input('type_id'), 
                    $request->input('year_id')
                )
                ->get();
        } else {
            $data = Slip::offLoggedUserPay()
                ->get();
        }

        $foundYear = Slip::offLoggedUserPay()
            ->foundYear()
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
            ->editColumn('namafile',function($slip){
                // return '<a href="#" class="btn btn-info">Download</a>';
                return '<a class="btn btn-primary" href="' . route('payslip.download', $slip->namafile) .'"><i class="fa fa-download"></i>&nbsp;&nbsp;download</a>';
            })
            ->editColumn('periode', function($data){
                $format = Carbon::createFromFormat('mdy', $data->periode)->month ;
                return  date("F", mktime(0, 0, 0, $format, 1));
            })
            // ->editColumn('namafile','utility.button')
            ->rawColumns(['namafile'])
                ->make(true);
        }


        $htmlBuilder->minifiedAjax('', 
            'data.type_id = $("#type-filter option:selected").val();
            data.year_id = $("#year-filter option:selected").val();', 
        [ ]);

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'searching' => false,
            'paging' => false,
            'responsive' => ['details' => true],
            'dom' =>  "<'form-inline'<'typeperiod form-group m-r-10'><'yearperiod form-group m-r-10'>>trip",  
            "language" => [
                'processing' => '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span> ',
                'lengthMenu' => '_MENU_',
                'search' => '<i class="fa fa-search"></i>',
            ],
        ]);


        $html = $htmlBuilder
        ->addColumn(
            ['data' => 'tipe',
             'name' => 'tipe','
             searchable' => true,
            'title' => 'Tipe',
            'orderable' => false,
            'id' => 'id',
            'width' => 
            '20%'])
        ->addColumn(
            ['data' => 'periode',
                'name' => 'periode', 
                'title' => 'Periode', 
                'orderable' => false, 
                'id' => 'periode', 
                'width' => '20%'])
                
        ->addColumn(
            [
            'data' => 'namafile', 
             'name' => 'namafile', 
            'title' => 'document', 
            'orderable' => false, 
            'id' => 'namafile', 
            'width' => '20%']);
        

        // tampilkan view index dengan tambahan script html DataTables
        return view('payslip.index')->with(compact('html','foundYear', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
