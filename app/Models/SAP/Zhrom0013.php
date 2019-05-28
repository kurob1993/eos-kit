<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;

class Zhrom0013 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zhrom0013';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function transition()
    {
        return $this->belongsTo('App\Models\Transition','abbr_jobs','nojabatan');
    }

    public function scopeGetForSelect2($query,$request)
    {           
        $page = $request->page;
        $resultCount = 25;
        $offset = ($page - 1) * $resultCount;

        $user = $query->select('nojabatan','namajabatan')
                ->where('nojabatan', 'like','%'.$request->term.'%')
                ->orWhere('namajabatan', 'like','%'.$request->term.'%')
                ->distinct()
                ->get();

        $results = $user
        ->map(function ($value,$key) {
            return [
                'id' => $value->nojabatan, 
                'text'=> $value->namajabatan
            ];
        });

        $count = $user->count();
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $select2 = ['results'=>$results,'pagination'=>['more'=>$morePages]];
        return response()->json($select2);
    }
}
