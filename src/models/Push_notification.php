<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class Push_notification extends Model
{
    use Uuid32ModelTrait;

	public function device()
	{
		return $this->belongsTo("App\Models\Push_device", "device_id");
	}

    public function send()
    {
        if ($this->device->application->type_id == 1)
        {
            $this->sendIOS();
        }
        else
        {
            $this->sendFCM();
        }
    }

    public function sendIOS() {
        $deviceToken = $this->device->device_token;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->device->application->pem_file->path());
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->device->application->pem_password);
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        $body['aps'] = array(
            'alert' => array(
                'title' => $this->title,
                'body' => $this->message,
             ),
            'sound' => 'default'
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
        if (!$result)
        {
            $this->status_id = 3;
        }
        else
        {
            $this->status_id = 2;
        }
        $this->save();
    }

    public function sendFCM()
    {
        $client = new \GuzzleHttp\Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'key='.$this->device->application->server_key];
        $body = ["data" => ["message" => $this->message], "to" => $this->device->device_token];
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', ["headers" => $headers, "json" => $body]);
        $object = json_decode($response->getBody());
        if ($object->success == 1)
        {
            $this->status_id = 2;
        }
        else
        {
            $this->status_id = 3;
        }
        $this->save();
        return $response;
    }
}
