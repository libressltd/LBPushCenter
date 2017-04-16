<?php

namespace LIBRESSLtd\LBPushCenter\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Push_device;
use DB;

class Push_deviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("vendor.LBPushCenter.device.index");
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

    public function removeDuplicatedDevice()
    {
        DB::statement("DELETE FROM push_devices WHERE id in (select id from  (select id, count(*) as no_device from push_devices group by device_token) a where no_device > 1)");
        return redirect()->back();
    }

    public function recoverOtherProblemDevice()
    {
        Push_device::whereEnabled(3)->update(['enabled' => 1]);
        return redirect()->back();
    }

    public function removeBadTokenDevice()
    {
        Push_device::whereEnabled(2)->delete();
        return redirect()->back();
    }
}
