<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class StructJab extends Model
{
    protected $table = 'structjab';
    public $timestamps = false;

    public function scopeGetForSelect2($query,$request)
    {           
        $page = $request->page;
        $resultCount = 25;
        $offset = ($page - 1) * $resultCount;

        $user = $query->select('no','jabatan')
                ->where('no', 'like','%'.$request->term.'%')
                ->orWhere('jabatan', 'like','%'.$request->term.'%')
                ->distinct()
                ->get();

        $results = $user
        ->map(function ($value,$key) {
            return [
                'id' => $value->no, 
                'text'=> $value->jabatan
            ];
        });

        $count = $user->count();
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $select2 = ['results'=>$results,'pagination'=>['more'=>$morePages]];
        return response()->json($select2);
    }
}
