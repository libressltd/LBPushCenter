<?php

namespace LIBRESSLtd\LBPushCenter\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Push_device;

class Push_deviceNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($device_id)
    {
        $device = Push_device::findOrFail($device_id);
        $device->send("test", "test");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($device_id)
    {
        return view("vendor.LBPushCenter.device_notification.add", ["device_id" => $device]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $device_id)
    {
        if ($device_id === "all")
        {
            $devices = Push_device::all();
            foreach ($devices as $device)
            {
                $device->sendInQueue($request->title, $request->description);
            }
            return redirect()->back();
        }
        else
        {
            $device = Push_device::findOrFail($device_id);
            $device->sendInQueue($request->title, $request->description);
            return redirect()->back();
        }
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
        return $device->send("test123123", "test");
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
