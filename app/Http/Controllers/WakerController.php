<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\SAP\Waker;

class WakerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->has('month_id') && $request->has('year_id')) {
            $wakers = Waker::ofLoggedUser()
                ->orderBy('checktime', 'DESC')
                ->monthYearOf(
                    $request->input('month_id'), 
                    $request->input('year_id')
                )
                ->get();
        } else {
            $wakers = Waker::ofLoggedUser()
                ->orderBy('checktime', 'DESC')
                ->get();
        }

        $foundYears = Waker::ofLoggedUser()
            ->foundYear()
            ->get();

        if ($request->ajax()) {
            return DataTables::of($wakers)
                ->make(true);
        }

        $htmlBuilder->minifiedAjax('', 
            'data.month_id = $("#month-filter option:selected").val();
            data.year_id = $("#year-filter option:selected").val();', 
        [ ]);

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'searching' => false,
            'paging' => false,
            'responsive' => ['details' => true],
            'dom' =>    "<'row'<'col-sm-2'<'monthperiod'>> <'col-sm-2'<'yearperiod'>>>" .
                "<'row'<'col-sm-12'tr>>" .
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "language" => [
                'processing' => '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span> ',
                'lengthMenu' => '_MENU_',
                'search' => '<i class="fa fa-search"></i>',
            ],
        ]);

        $html = $htmlBuilder->columns([
            ['data' => 'checktime', 'name' => 'checktime', 'title' => 'Check Time', 'orderable' => false, 'width' => '20%'],
            ['data' => 'checktype', 'name' => 'checktype', 'title' => 'In/Out', 'orderable' => false, 'width' => '20%'],
            ['data' => 'machinenumber', 'name' => 'machinenumber', 'title' => 'Machine', 'orderable' => false, 'width' => '20%'],
        ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('wakers.index')->with(compact('html', 'wakers', 'foundYears'));
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
