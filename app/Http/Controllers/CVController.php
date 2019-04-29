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
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data personal data untuk user yang telah login
        $personalData = PersonalData::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'CNAME', 'GBDAT', 'GBORT',
                'T502T_FATXT', 'T516T_KNFTX', 'FAMDT', 'AEDTM'
            ])
            ->lastEndDate()
            ->first();

        // ambil data addresses untuk user yang telah login
        $addresses = Address::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'STRAS', 'LOCAT', 'ORT01', 'PSTLZ',
                'ORT02', 'T005U_BEZEI', 'TELNR', 'AEDTM'
            ])
            ->get();
        $lastUpdatedAddresses = Address::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data families untuk user yang telah login
        $families = Family::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'FCNAM', 'FGBDT', 'FGBOT', 'FASEX',
                'FASEX_DESC', 'KDZUG', 'AEDTM'
            ])
            ->get();
        $lastUpdatedFamilies = Family::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data educations untuk user yang telah login
        $educations = Education::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T517T_STEXT', 'INSTI', 'LANDX50', 
                'T517X_FTEXT', 'AEDTM'
            ])
            ->get();
        $lastUpdatedEducations = Education::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data trainings untuk user yang telah login
        $trainings = Training::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'TRAIN', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $lastUpdatedTrainings = Training::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data internalActivities untuk user yang telah login
        $internalActivities = InternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $lastUpdatedInternalActivities = Training::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data externalActivities untuk user yang telah login
        $externalActivities = ExternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'ORGNM', 'ZZPOSISI',
                'STRAS', 'ORT01', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $lastUpdatedExternalActivities = Training::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data others untuk user yang telah login
        $others = Other::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2', 'PTEXT_LINE3', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $lastUpdatedOthers = Training::sapOfLoggedUser()
            ->max('BEGDA');

        // tampilkan view index dengan tambahan script html DataTables
        return view('curriculum_vitaes.index')
            ->with(
                compact(
                    'personalData',
                    'addresses', 'lastUpdatedAddresses',
                    'families', 'lastUpdatedFamilies',
                    'educations', 'lastUpdatedEducations',
                    'trainings', 'lastUpdatedTrainings',
                    'internalActivities', 'lastUpdatedInternalActivities',
                    'externalActivities', 'lastUpdatedExternalActivities',
                    'others', 'lastUpdatedOthers'
                )
            );
    }

    public function download()
    {
        
    }
}
