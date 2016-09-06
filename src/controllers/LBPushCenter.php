
<?php

namespace LIBRESSLtd\LBPushCenter\Controllers;
use GuzzleHttp\Client;

class LBPushCenter
{
	static function push($devices, $message)
	{
		$push_items = array();
		foreach ($devices as $device)
        {
                $push_items[] = array(
                        "device_type" => $device["type"],
                        "device_token" => $device["token"],
                        "message" => $message
                );
        }
        $client = new Client();
	    $res = $client->postAsync('http://ltm.libre.com.vn:20000/services/pushcenter/push', ['json' => ['device_items' => $push_items]]);
    }
}