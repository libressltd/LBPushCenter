<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;

class Push_notification extends Model
{
    use Uuid32ModelTrait, LBDatatableTrait;

    protected $fillable = ['status_id'];

    // public function

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

    public function sendIOSConnect()
    {
        $deviceToken = $this->device->device_token;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->device->application->pem_file->path());
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->device->application->pem_password);
        $fp;
        if ($this->device->application->production_mode)
        {
            $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        }
        else
        {
            $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        }
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        return $fp;
    }

    public function sendIOSContinuosly($fp)
    {
        echo "[iOS] ".$this->device->device_tokenn." : ".$this->message." ";
        $body['aps'] = array(
            'alert' => array(
                'title' => $this->title,
                'body' => $this->message
             ),
            'badge' => $this->device->badge() + 1,
            'sound' => 'default'
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $this->device->device_token) . pack('n', strlen($payload)) . $payload;
        $result = false;
        try {
            $result = fwrite($fp, $msg, strlen($msg));
        } 
        catch (\Exception $e) {
            fclose($fp);
            echo "Failed\n";
            $this->status_id = 3;
            $this->device->enabled = 0;
            $this->device->save();
            $fp = $this->sendIOSConnect();
        }
        if (!$result)
        {
            $this->status_id = 3;
        }
        else
        {
            $this->status_id = 2;
            echo "Done\n";
        }
        $this->save();
        return $fp;
    }

    public function sendIOS() {
        $fp = $this->sendIOSConnect();

        $notifications = Push_notification::whereHas('device', function ($query) {
            $query->whereApplicationId($this->device->application_id);
        })->whereStatusId(1)->limit(100)->get();
        foreach ($notifications as $notification)
        {
            $fp = $notification->sendIOSContinuosly($fp);
        }

        fclose($fp);
    }

    public function sendFCM()
    {
        echo "[Adr] ".$this->device->device_tokenn." : ".$this->message." ";
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

    // relationship

    public function device()
    {
        return $this->belongsTo("App\Models\Push_device", "device_id");
    }

    // scope

    public function scopeNew($query)
    {
        return $query->where("status_id", 1);
    }

    public function scopeSent($query)
    {
        return $query->where("status_id", 2);
    }

    public function scopeFail($query)
    {
        return $query->where("status_id", 3);
    }

    public function scopeRead($query)
    {
        return $query->where("status_id", 4);
    }
}
