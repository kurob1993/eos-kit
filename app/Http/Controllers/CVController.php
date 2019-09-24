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
use App\Models\SAP\Position;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    private $cvs;

    private function initialize()
    {
        // ambil data position untuk user yang telah login
        $this->cvs['position'] = Position::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'PERNR', 'PERSK', 'T503T_PTEXT',
                'HRP1000_O_STEXT', 'HRP1000_S_SHORT', 'HRP1000_S_STEXT',
            ])
            ->lastEndDate()
            ->first();

        // ambil data personal data untuk user yang telah login
        $this->cvs['personalData'] = PersonalData::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'CNAME', 'GBDAT', 'GBORT',
                'T502T_FATXT', 'T516T_KNFTX', 'FAMDT', 'AEDTM'
            ])
            ->lastEndDate()
            ->first();

        // ambil data addresses untuk user yang telah login
        $this->cvs['addresses']['data'] = Address::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'STRAS', 'LOCAT', 'ORT01', 'PSTLZ',
                'ORT02', 'T005U_BEZEI', 'TELNR', 'AEDTM'
            ])
            ->get();
        $this->cvs['addresses']['last_updated'] = Address::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data families untuk user yang telah login
        $this->cvs['families']['data'] = Family::sapOfLoggedUser()
            ->select([
                'T591S_STEXT', 'FCNAM', 'FGBDT', 'FGBOT', 'FASEX',
                'FASEX_DESC', 'KDZUG', 'AEDTM'
            ])
            ->get();
        $this->cvs['families']['last_updated'] = Family::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data educations untuk user yang telah login
        $this->cvs['educations']['data'] = Education::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T517T_STEXT', 'INSTI', 'LANDX50',
                'T517X_FTEXT', 'AEDTM'
            ])
            ->get();
        $this->cvs['educations']['last_updated'] = Education::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data trainings untuk user yang telah login
        $this->cvs['trainings']['data'] = Training::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'TRAIN', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $this->cvs['trainings']['last_updated'] = Training::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data internalActivities untuk user yang telah login
        $this->cvs['internalActivities']['data'] = InternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $this->cvs['internalActivities']['last_updated'] = InternalActivity::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data externalActivities untuk user yang telah login
        $this->cvs['externalActivities']['data'] = ExternalActivity::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'ORGNM', 'ZZPOSISI',
                'STRAS', 'ORT01', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $this->cvs['externalActivities']['last_updated'] = ExternalActivity::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data others untuk user yang telah login
        $this->cvs['others']['data'] = Other::sapOfLoggedUser()
            ->select([
                'BEGDA', 'ENDDA', 'T591S_STEXT', 'PTEXT_LINE1',
                'PTEXT_LINE2', 'PTEXT_LINE3', 'AEDTM'
            ])
            ->orderBy('BEGDA', 'DESC')
            ->get();
        $this->cvs['others']['last_updated'] = Other::sapOfLoggedUser()
            ->max('BEGDA');

        // ambil data activity internal. external, other
        $this->cvs['intActivities']['data'] = Activity::ofLoggedUser()
            ->where('type','internal')
            ->sentToSapOnly()
            ->orderBy('start_date', 'DESC')
            ->get();
        $this->cvs['intActivities']['last_updated'] = Activity::ofLoggedUser()
            ->where('type','internal')
            ->sentToSapOnly()
            ->max('updated_at');

        $this->cvs['extActivities']['data'] = Activity::ofLoggedUser()
            ->where('type','external')
            ->sentToSapOnly()
            ->orderBy('start_date', 'DESC')
            ->get();
        $this->cvs['extActivities']['last_updated'] = Activity::ofLoggedUser()
            ->where('type','external')
            ->sentToSapOnly()
            ->max('updated_at');

        $this->cvs['otherActivities']['data'] = Activity::ofLoggedUser()
            ->where('type','other')
            ->sentToSapOnly()
            ->orderBy('start_date', 'DESC')
            ->get();
        $this->cvs['otherActivities']['last_updated'] = Activity::ofLoggedUser()
            ->where('type','other')
            ->sentToSapOnly()
            ->max('updated_at');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        // inisialisasi data curriculum vitae
        $this->initialize();
        $cvs = $this->cvs;
        extract($cvs, EXTR_PREFIX_SAME, "wddx");

        // tampilkan view index dengan tambahan script html DataTables
        return view(
            'curriculum_vitaes.index',
            compact(
                'personalData',
                'addresses',
                'families',
                'educations',
                'trainings',
                'internalActivities',
                'externalActivities',
                'others',
                'intActivities',
                'extActivities',
                'otherActivities'
            )
        );
    }

    public function download()
    {
        // inisialisasi data curriculum vitae
        $this->initialize();
        $cvs = $this->cvs;
        extract($cvs, EXTR_PREFIX_SAME, "wddx");

        if (Storage::disk('public')->exists('pic/' . $position->PERNR . '.jpg'))
            $picture = asset('storage/pic/' . $position->PERNR . '.jpg');
        else
            $picture = url('/images/default.png');

        // tampilkan view index dengan tambahan script html DataTables
        return view(
            'curriculum_vitaes.download',
            compact(
                'position',
                'personalData',
                'addresses',
                'families',
                'educations',
                'trainings',
                'internalActivities',
                'externalActivities',
                'others',
                'picture',
                'intActivities',
                'extActivities',
                'otherActivities'
            )
        );
    }
}
