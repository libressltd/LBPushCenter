<?php

namespace LIBRESSLtd\LBPushCenter\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Push_device;
use App\Models\Push_application;


class Push_deviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $application = Push_application::findOrFail($request->application);
        $device = new Push_device();
        $device->device_token = $request->token;
        $device->application_id = $application->id;
        $device->save();

        return $device;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $device = Push_device::findOrFail($id);
        $device->send("test title", "test message");
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
        $device = Push_device::where("id", $id)->orWhere("device_token", $id)->firstOrFail();
        $device->enabled = $request->enabled;
        $device->save();

        return response(["code" => 200, "description" => "success", "device" => $device], 200);
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

    public function postClearBadge($device_id)
    {
        $device = Push_device::where("id", $device_id)->orWhere("device_token", $device_id)->firstOrFail();
        $device->clear_badge();

        return response(["code" => 200, "description" => "success", "badge" => $device->badge()], 200);
    }
}
