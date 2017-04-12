<?php

namespace LIBRESSLtd\LBPushCenter\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Push_notification;
use App\Models\Push_notification_sent;
use Carbon\Carbon;

class Push_notificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Push_notification::with("device", "device.application.type")->orderBy("created_at", "desc")->datatable(request());
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == 'all')
        {
            $array = [];
            for ($i = 0; $i < 30; $i ++)
            {
                $array[] = Push_notification_sent::where("created_at", "<", Carbon::now()->addSeconds( - 5 * $i))->where("created_at", ">=", Carbon::now()->addSeconds( - 5 * $i - 5))->count();
            }
            return $array;
        }
        if ($id == 'static')
        {
            $array = [
                "notification_pending" => Push_notification::count()
            ];
            return $array;
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
