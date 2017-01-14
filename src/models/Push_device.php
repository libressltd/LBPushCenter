<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use App\Models\Push_application;
use GuzzleHttp\Client;

class Push_device extends Model
{
    use Uuid32ModelTrait;

    static function add($token, $app_name)
    {
        $app = Push_application::where("name", $app_name)->firstOrFail();
        $device = Push_device::where("device_token", $token)->where("application_id", $app->id)->first();
        if (!$device)
        {
            $device = new Push_device;
        }
        $device->device_token = $token;
        $device->app_id = $app->id;
        $device->save();

        return $device;
    }

    public function send($title, $desc)
    {
        if ($this->application->type_id == 1)
        {
            $this->sendIOS($title, $desc);
        }
        else
        {
            $this->sendFCM($title, $desc);
        }
    }

    public function sendIOS($title, $desc) {
        $deviceToken = $this->device_token;
        $ctx = stream_context_create();
        // ck.pem is your certificate file
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->application->pem_file->path());
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->application->pem_password);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => $title,
                'body' => $desc,
             ),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        
        // Close the connection to the server
        fclose($fp);
        if (!$result)
            return 'Message not delivered' . PHP_EOL;
        else
            return 'Message successfully delivered' . PHP_EOL;
    }

    public function sendFCM($title, $desc)
    {
        $client = new \GuzzleHttp\Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'key='.$this->application->server_key];
        $body = ["data" => ["message" => $title], "to" => $this->device_token];
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', ["headers" => $headers, "json" => $body]);

        return $response;
    }

    // relationship

    public function application()
    {
        return $this->belongsTo("App\Models\Push_application", "application_id");
    }

    public function users()
    {
        return $this->belongsToMany("App\Models\User", "push_user_devices", "device_id", "user_id");
    }
}
