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
        $wakers = Waker::ofLoggedUser()
            ->orderBy('checktime', 'DESC')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($wakers)
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'searching' => false,
            'responsive' => ['details' => false],
        ]);

        $html = $htmlBuilder->columns([
            ['data' => 'checktime', 'name' => 'checktime', 'title' => 'Check Time', 'orderable' => false],
            ['data' => 'checktype', 'name' => 'checktype', 'title' => 'In/Out', 'orderable' => false],
            ['data' => 'machinenumber', 'name' => 'machinenumber', 'title' => 'Machine', 'orderable' => false],
        ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('wakers.index')->with(compact('html', 'wakers'));
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
