<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrgText;

class OrganizationController extends Controller
{

    public function index()
    {
        $paginated = OrgText::lastOrg()->get();

        return $paginated;
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

    public function show($ObjectID, $date = null)
    {
        if (is_null($date)) {
            return OrgText::findByObjectID($ObjectID)
                ->lastOrg()
                ->first();
        } else {
            return OrgText::findByCompositeKey($ObjectID, $date)
                ->first();
        }
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

    public function unitkerja($unitkerja, $date = null)
    {
       return OrgText::lastUk($unitkerja, $date)->get();       
    }

    public function unitkerjaUk($unitkerja)
    {
       return OrgText::oldDiv($unitkerja)->get();       
    }

}
