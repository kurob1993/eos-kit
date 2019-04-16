<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use App\Models\SAP\Address;
use App\Models\SAP\PersonalData;
use App\Models\SAP\Family;
use App\Models\SAP\Education;
use App\Models\SAP\Training;
use App\Models\SAP\InternalActivity;
use App\Models\SAP\ExternalActivity;
use App\Models\SAP\Other;

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
            ->select([
                'BEGDA', 'ENDDA', 'CNAME', 'GBDAT', 'GBORT',
                'T502T_FATXT', 'T516T_KNFTX'
            ])
            ->lastEndDate()
            ->first();

        // ambil data addresses untuk user yang telah login
        $addresses = Address::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'STRAS', 'LOCAT', 'ORT01', 'PSTLZ',
                'ORT02', 'T005U_BEZEI'
            ])
            ->get();

        // ambil data families untuk user yang telah login
        $families = Family::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'FCNAM', 'FGBDT', 'FGBOT', 'FASEX',
                'FASEX_DESC', 'KDZUG'
            ])
            ->get();

        // ambil data educations untuk user yang telah login
        $educations = Education::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T517T_STEXT', 'INSTI', 'LANDX50', 
                'T517X_FTEXT'
            ])
            ->get();

        // ambil data trainings untuk user yang telah login
        $trainings = Training::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'TRAIN'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();

        // ambil data internalActivities untuk user yang telah login
        $internalActivities = InternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();

        // ambil data externalActivities untuk user yang telah login
        $externalActivities = ExternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'ORGNM', 'ZZPOSISI',
                'STRAS', 'ORT01'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();

        // ambil data others untuk user yang telah login
        $others = Other::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2', 'PTEXT_LINE3'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();

        // tampilkan view index dengan tambahan script html DataTables
        return view('curriculum_vitaes.index')
            ->with(
                compact(
                    'personalData',
                    'addresses',
                    'families',
                    'educations',
                    'trainings',
                    'internalActivities',
                    'externalActivities',
                    'others'
                )
            );
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
