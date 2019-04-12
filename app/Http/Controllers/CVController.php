<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SAP\PersonalData;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;    

class CVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data personal data untuk user yang telah login
        $personalData = PersonalData::sapOfLoggedUser()
            ->select([  'BEGDA', 'ENDDA', 'CNAME', 'GBDAT', 'GBORT', 
                        'T502T_FATXT', 'T516T_KNFTX'])
            ->get();

        if ($request->ajax()) {
            return DataTables::of($personalData)
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => false,
            'searching' => false,
            'responsive' => ['details' => false],
        ]);

        $html = $htmlBuilder->columns([
            ['data' => 'BEGDA', 'name' => 'BEGDA', 'title' => 'Mulai'],
            ['data' => 'ENDDA', 'name' => 'ENDDA', 'title' => 'Berakhir'],
            ['data' => 'CNAME', 'name' => 'CNAME', 'title' => 'Nama'],
            ['data' => 'GBDAT', 'name' => 'GBDAT', 'title' => 'Tanggal Lahir'],
            ['data' => 'GBORT', 'name' => 'GBORT', 'title' => 'Tempat Lahir'],
            ['data' => 'T502T_FATXT', 'name' => 'T502T_FATXT', 'title' => 'Status Nikah'],
            ['data' => 'T516T_KNFTX', 'name' => 'T516T_KNFTX', 'title' => 'Agama'],
        ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('curriculum_vitaes.index')->with(compact('html', 'personalData'));
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
