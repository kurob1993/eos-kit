<?php

namespace App\Http\Controllers;
use App\Models\Director;
use Illuminate\Http\Request;
use Alert;
use App\Http\Requests\DirectorPost;
use Illuminate\Support\Facades\Session;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dir = Director::foundDirectors()->get();
        $com = Director::foundCommisary()->get();
        return view('directors.directors',['dir'=> $dir,'com'=> $com]);
    }

    public function getcommisary(){
        $data = Director::foundCommisary()->get();
        return view('directors.commisary',['data'=> $data]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('directors.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirectorPost $request)
    {
        $data = new Director;
        $data->no = $request->get('no');
        $data->emp_hrp1000_s_short = $request->get('emp_hrp1000_s_short');
        $data->emppostx = $request->get('emppostx');
        $data->emportx = $request->get('emportx');
        $data->empnik = $request->get('empnik');
        $data->empname = strtoupper($request->get('empname'));
        $data->ttl = $request->get('ttl');
        $data->LSTUPDT = $request->get('LSTUPDT');
        $data->emppersk = $request->get('emppersk');
        $data->empposid = $request->get('empposid');
        $data->save();
        return redirect()->route('direksi.index');
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
    public function edit($empnik)
    {

        $data = Director::findOrFail((int)$empnik);
        return view('directors.edit',['data'=> $data]);
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$empnik)
    {
        $data =  Director::findOrFail((int)$empnik);
        $data->no = $request->get('no');
        $data->emp_hrp1000_s_short = $request->get('emp_hrp1000_s_short');
        $data->emppostx = $request->get('emppostx');
        $data->emportx = $request->get('emportx');
        $data->empnik = $request->get('empnik');
        $data->empname = $request->get('empname');
        $data->ttl = $request->get('ttl');
        $data->LSTUPDT = $request->get('LSTUPDT');
        $data->emppersk = $request->get('emppersk');
        $data->empposid = $request->get('empposid');
        $data->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan data baru.",
        ]);
        return redirect()->route('direksi.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($empnik)
    {
        // return var_dump((int)$empnik);
        $data = Director::findOrFail((int)$empnik);
        $data->delete();
        return redirect()->route('direksi.index')->with('status','Category successfully moved to trash');
    }
}
