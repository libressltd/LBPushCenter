<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;
use App\Models\Push_application;
use GuzzleHttp\Client;
use App\Models\Push_notification_sent;

class Push_notification extends Model
{
    use Uuid32ModelTrait, LBDatatableTrait;
    protected $fillable = ['status_id'];

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

    public function sendFCM()
    {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'key='.$this->device->application->server_key];
        $body = [
            "notification" => [
                "title" => $this->title,
                "body" => $this->message
            ], 
            "to" => $this->device->device_token
        ];
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', ["headers" => $headers, "json" => $body]);
        $object = json_decode($response->getBody());
        if ($object->success == 1)
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 2]);
        }
        else
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
    }

    public function sendIOS()
    {
        defined('CURL_VERSION_HTTP2') || define('CURL_VERSION_HTTP2', 65536);
        defined('CURL_HTTP_VERSION_2_0') || define('CURL_HTTP_VERSION_2_0', 3);
        defined('CURL_HTTP_VERSION_2') || define('CURL_HTTP_VERSION_2', CURL_HTTP_VERSION_2_0);
        defined('CURLPIPE_NOTHING') || define('CURLPIPE_NOTHING', 0);
        defined('CURLPIPE_HTTP1') || define('CURLPIPE_HTTP1', 1);
        defined('CURLPIPE_MULTIPLEX') || define('CURLPIPE_MULTIPLEX', 2);

        $client = new Client();
        $device_token = $this->device->device_token;
        $application = $this->device->application;
        $path;
        if ($application->production_mode)
        {
            $path = "https://api.push.apple.com/3/device/$device_token";
        }
        else
        {
            $path = "https://api.development.push.apple.com/3/device/$device_token";
        }
        $response = $client->request('POST', $path, [
            'curl' => [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            ],
            'headers' => [
                'apns-id' => preg_replace("/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/i", "$1-$2-$3-$4-$5", $this->id)
            ],
            'json' => [
                'aps' => [
                    'alert' => [
                        "title" => $this->title,
                        "body" => $this->message
                    ],
                    'sound' => 'default',
                    'badge' => $this->badge
                ]
            ],
            'cert' => [
                $application->pem_file->path(),
                $application->pem_password
            ]
        ]);
        if ($response->getStatusCode() == 200)
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 2]);
        }
        else
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
        print_r($response->getHeader('content-type'));
        print_r($response->getBody());
    }

    // relationship
    public function device()
    {
        return $this->belongsTo("App\Models\Push_device", "device_id");
    }
}