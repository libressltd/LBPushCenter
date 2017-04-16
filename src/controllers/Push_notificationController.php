<?php

namespace LIBRESSLtd\LBPushCenter\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Push_notification;
use App\Models\Push_device;
use Webpatser\Uuid\Uuid;
use DB;

class Push_notificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor.LBPushCenter.notification.index');
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
        if ($request->application_id == -1)
        {
            Push_device::whereEnabled(1)->chunk(1000, function($devices) use ($request) {
                $push_array = [];

                foreach ($devices as $device)
                {
                    $uuid = Uuid::generate(4);
                    $push_array[] = [
                        'id' => str_replace('-', '', $uuid->string),
                        'device_id' => $device->id,
                        'title' => $request->title,
                        'message' => $request->description,
                        'created_at' => DB::raw("NOW()"),
                    ];
                }
                Push_notification::insert($push_array);
            });
        }
        else
        {
            Push_device::whereApplicationId($request->application_id)->whereEnabled(1)->chunk(1000, function($devices) use ($request) {
                $push_array = [];

                foreach ($devices as $device)
                {
                    $uuid = Uuid::generate(4);
                    $push_array[] = [
                        'id' => str_replace('-', '', $uuid->string),
                        'device_id' => $device->id,
                        'title' => $request->title,
                        'message' => $request->description,
                        'created_at' => DB::raw("NOW()"),
                    ];
                }
                Push_notification::insert($push_array);
            });
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notificaiton = Push_notification::whereStatusId(1)->whereId($id)->first();
        if ($notificaiton)
        {
            $notificaiton->send();
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
}
