<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class Push_application extends Model
{
    use Uuid32ModelTrait;

    // relationship

    public function type()
    {
    	return $this->belongsTo("App\Models\Push_application_type", "type_id");
    }

    public function pem_file()
    {
    	return $this->belongsTo("App\Models\Media", "pem_file_id");
    }

    public function devices()
    {
        return $this->hasMany("App\Models\Push_device", "application_id");
    }

    public function notifications()
    {
        return $this->hasManyThrough("App\Models\Push_notification", "App\Models\Push_device", "application_id", "device_id", "id");
    }

    // iOS

    public function ios_connect()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->pem_file->path());
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->pem_password);
        $fp;
        if ($this->production_mode)
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

    public function ios_send($fp, $notification)
    {
        $body['aps'] = array(
            'alert' => array(
                'title' => $notification->title,
                'body' => $notification->message
             ),
            'badge' => $notification->device->badge() + 1,
            'sound' => 'default'
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $notification->device->device_token) . pack('n', strlen($payload)) . $payload;
        $result = false;
        try {
            $result = fwrite($fp, $msg, strlen($msg));
        } 
        catch (\Exception $e) {
            echo $e;
            fclose($fp);
            $fp = $this->sendIOSConnect();
        }
        return $fp;
    }

    public function ios_send_all()
    {
        $fp = $this->ios_connect();
        $this->notifications()->with("device")->chunk(1000, function($notification) {
            $fp = $this->ios_send($fp, $notification);
        });
        fclose($fp);
    }
}
