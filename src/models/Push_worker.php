<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use Carbon\Carbon;
use App\Models\Push_notification;
use GuzzleHttp\Client;
use App\Models\Push_notification_sent;
use DB;

class Push_worker extends Model
{
    use Uuid32ModelTrait;
    protected $appends = ["is_offline", "is_inactive"];

    public function start_work()
    {
        $notification = $this->notifications()->with("device")->first();

        $application = $notification->device->application;
        $title = $notification->title;
        $message = $notification->message;


        $notifications = $this->findSame($notification, $application, $title, $message);

        if ($notifications->count() > 0)
        {
            $this->sendPush($notifications, $application);
        }
    }

    // push with bunch of device

    public function sendPush($notifications, $application)
    {
        if ($application->type_id == 1)
        {
            $this->sendPushIOS($notifications, $application);
        }
        else
        {
            $this->sendPushAndroid($notifications, $application);
        }
    }

    public function sendPushIOS($notifications, $application)
    {
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }
        // open connection 
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        $http2_server = 'https://api.development.push.apple.com';
        if ($application->production_mode)
        {
            $http2_server = 'https://api.push.apple.com';
        }
        $app_bundle_id = $application->server_key;
        $apple_cert = $application->pem_file->path();
        $apple_pass = $application->pem_password;
        // send push

        $result_array = ["success" => [], "BadDeviceToken" => [], "OtherProblem" => []];

        foreach ($notifications as $n)
        {
            $message = json_encode([
                "aps" => [
                    "alert" => [
                        "title" => $n->title,
                        "body" => $n->message,
                    ],
                    "sound" => "default",
                    "badge" => 1
                ]
            ]);
            $token = $n->device->device_token;
            // url (endpoint)
            $url = "{$http2_server}/3/device/{$token}";
         
            // certificate
            $cert = realpath($apple_cert);
         
            // headers
            $headers = array(
                "apns-topic: {$app_bundle_id}",
                "User-Agent: My Sender"
            );

            echo "$message $token $url $apple_pass ". curl_error($http2ch);
         
            // other curl options
            curl_setopt_array($http2ch, array(
                CURLOPT_URL => $url,
                CURLOPT_PORT => 443,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $message,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSLCERT => $cert,
                CURLOPT_SSLCERTPASSWD => $apple_pass,
                CURLOPT_HEADER => 1
            ));
         
            // go...
            $result = curl_exec($http2ch);

            if ($result === FALSE) {
                echo curl_error($http2ch);
                exit();
            }
         
            // get response
            $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($http2ch, CURLINFO_HEADER_SIZE);
            $header = substr($result, 0, $header_size);
            $body = substr($result, $header_size);
            $object = json_decode($body);

            if ($status == 200)
            {
                $success_array = $result_array["success"];
                $success_array[] = $n->id;
                $result_array["success"] = $success_array;
            }
            else if ($status == 400 &&  $object->reason == "BadDeviceToken")
            {
                $success_array = $result_array["BadDeviceToken"];
                $success_array[] = $n->id;
                $result_array["BadDeviceToken"] = $success_array;
            }
            else
            {
                $success_array = $result_array["OtherProblem"];
                $success_array[] = $n->id;
                $result_array["OtherProblem"] = $success_array;
            }
        }

        curl_close($http2ch);

        if (count($result_array["success"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["success"])->update(["status_id" => 2, "updated_at" => DB::raw("NOW()")]);
        }
        if (count($result_array["BadDeviceToken"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["BadDeviceToken"])->update(["status_id" => 3, "response_code" => 400, "response_string" => "BadDeviceToken",  "updated_at" => DB::raw("NOW()")]);
        }
        if (count($result_array["OtherProblem"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["OtherProblem"])->update(["status_id" => 3, "response_code" => 410, "response_string" => "OtherProblem",  "updated_at" => DB::raw("NOW()")]);
        }
    }

    public function sendPushAndroid($notifications, $application)
    {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'key='.$application->server_key];
        $registration_ids = [];
        foreach ($notifications as $n)
        {
            $registration_ids[] = $n->device->device_token;
        }

        $devices = [];
        $title = "";
        $message = "";
        foreach ($notifications as $n)
        {
            $devices[] = $n->id;
            $title = $n->title;
            $message = $n->message;
        }

        $body = [
            "notification" => [
                "title" => $title,
                "body" => $message,
                "sound" => "default"
            ],
            "registration_ids" => $registration_ids
        ];
        if (isset($application->server_secret))
        {
            $body["restricted_package_name"] = $application->server_secret;
        }
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
            "headers" => $headers, 
            "json" => $body,
            'http_errors' => false
        ]);

        $object = json_decode($response->getBody());

        $result_array = ["success" => [], "InvalidRegistration" => [], "NotRegistered" => [], "InvalidPackageName" => []];
        for ($i = 0; $i < count($object->results); $i ++)
        {
            $result = $object->results[$i];
            $token = $registration_ids[$i];
            $device_id = $devices[$i];

            if (isset($result->message_id))
            {
                $success_array = $result_array["success"];
                $success_array[] = $device_id;
                $result_array["success"] = $success_array;
            }
            else if (isset($result->error))
            {
                if ($result->error == "InvalidRegistration")
                {
                    $success_array = $result_array["InvalidRegistration"];
                    $success_array[] = $device_id;
                    $result_array["InvalidRegistration"] = $success_array;
                }
                else if ($result->error == "NotRegistered")
                {
                    $success_array = $result_array["NotRegistered"];
                    $success_array[] = $device_id;
                    $result_array["NotRegistered"] = $success_array;
                }
                else if ($result->error == "InvalidPackageName")
                {
                    $success_array = $result_array["InvalidPackageName"];
                    $success_array[] = $device_id;
                    $result_array["InvalidPackageName"] = $success_array;
                }
            }
        }
        if (count($result_array["success"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["success"])->update(["status_id" => 2, "updated_at" => DB::raw("NOW()")]);
        }
        if (count($result_array["InvalidRegistration"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["InvalidRegistration"])->update(["status_id" => 3, "response_code" => 400, "response_string" => "InvalidRegistration",  "updated_at" => DB::raw("NOW()")]);
        }
        if (count($result_array["NotRegistered"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["NotRegistered"])->update(["status_id" => 3, "response_code" => 400, "response_string" => "NotRegistered",  "updated_at" => DB::raw("NOW()")]);
        }
        if (count($result_array["InvalidPackageName"]) > 0)
        {
            Push_notification_sent::whereIn("id", $result_array["InvalidPackageName"])->update(["status_id" => 3, "response_code" => 400, "response_string" => "InvalidPackageName",  "updated_at" => DB::raw("NOW()")]);
        }
    }

    // find same device

    public function findSame($notification, $application, $title, $message)
    {
        if ($application->type_id == 1)
        {
            return $this->findSameIOS($application->id);
        }
        else
        {
            return $this->findSameAndroid($application->id, $title, $message);
        }
    }

    public function findSameIOS($application_id)
    {
        return $this->notifications()->whereHas("device", function ($query) use ($application_id) {
            $query->whereApplicationId($application_id);
        })->with("device")->get();
    }

    public function findSameAndroid($application_id, $title, $message)
    {
        return $this->notifications()->whereHas("device", function ($query) use ($application_id) {
            $query->whereApplicationId($application_id);
        })->whereTitle($title)->whereMessage($message)->take(100)->with("device")->get();
    }

    // relationship & function

    public function notifications()
    {
        return $this->hasMany("App\Models\Push_notification", "worker_id");
    }

    public function isOffline()
    {
        return $this->updated_at->addMinutes(1)->isPast();
    }

    public function isInactive()
    {
        return $this->updated_at->addSeconds(30)->isPast();
    }

    public function getIsOfflineAttribute()
    {
        return $this->isOffline();
    }

    public function getIsInactiveAttribute()
    {
        return $this->isInactive();
    }

    public function clearNotification()
    {
        Push_notification::where("worker_id", $this->id)->update(["worker_id" => null]);
    }
}
