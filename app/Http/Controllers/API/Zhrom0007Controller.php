<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SAP\Zhrom0007;
use PhpParser\Node\NullableType;

class Zhrom0007Controller extends Controller
{
    public function index()
    {
        return Zhrom0007::all();
    }
    
    public function AbbrPosition($AbbrPosition = null)
    {
        return Zhrom0007::where('AbbrPosition',$AbbrPosition)->first();
    }

    public function AbbrDirektorat()
    {
        return Zhrom0007::distinct()
            ->get(['AbbrOrgUnitDirektorat','NameofOrgUnitDirektorat']);   
    }

    // funtion get name of Subdit
    public function AbbrOrganization($key = null, $id = null)
    {
        if($key == 2)
        {
            return Zhrom0007::distinct()
                ->where('AbbrOrgUnitDirektorat', $id)
                ->where('AbbrOrgUnitSubDirektorat','<>','')
                ->get(['AbbrOrgUnitSubDirektorat','NameofOrgUnitSubDirektorat']);   
        }
        elseif($key == 3)
        {
            return Zhrom0007::distinct()
                ->where('AbbrOrgUnitSubDirektorat', $id)
                ->where('AbbrOrgUnitDivisi','<>','')
                ->get(['AbbrOrgUnitDivisi','NameofOrgUnitDivisi']);
        }
        elseif($key == 4)
        {
            return Zhrom0007::distinct()
                ->where('AbbrOrgUnitDivisi', $id)
                ->where('AbbrOrgUnitDinas','<>','')
                ->get(['AbbrOrgUnitDinas','NameofOrgUnitDinas']);
        }
        elseif($key == 5)
        {
            return Zhrom0007::distinct()
                ->where('AbbrOrgUnitDinas', $id)
                ->where('AbbrOrgUnitSeksi','<>','')
                ->get(['AbbrOrgUnitSeksi','NameofOrgUnitSeksi']);
        }
    }

    public function AbbrPosisitionShow($param = null, $value = null, $level=null)
    {
        if($level == 'E')
        {
            return Zhrom0007::distinct()
                ->where($param, $value)
                ->whereIn('LvlOrg',['E','D'])
                ->get(['AbbrPosition','NameofPosition','LvlOrg']);
        }
        elseif($level == 'D')
        {
            return Zhrom0007::distinct()
                ->where($param, $value)
                ->whereIn('LvlOrg',['D','C'])
                ->get(['AbbrPosition','NameofPosition','LvlOrg']);
        }
        elseif($level == 'C')
        {
            return Zhrom0007::distinct()
                ->where($param, $value)
                ->whereIn('LvlOrg',['C','B'])
                ->get(['AbbrPosition','NameofPosition','LvlOrg']);
        }
        elseif($level == 'B')
        {
            return Zhrom0007::distinct()
                ->where($param, $value)
                ->whereIn('LvlOrg',['B','A'])
                ->get(['AbbrPosition','NameofPosition','LvlOrg']);
        }
        elseif($level == 'A')
        {
            return Zhrom0007::distinct()
                ->where($param, $value)
                ->whereIn('LvlOrg',['A'])
                ->get(['AbbrPosition','NameofPosition','LvlOrg']);
        }
        
    }
}
