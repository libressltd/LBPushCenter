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
        if (!$this->device)
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
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
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
            "headers" => $headers, 
            "json" => $body,
            'http_errors' => false
        ]);

        $object = json_decode($response->getBody());
        if ($response->getStatusCode() == 200 && $object)
        {
            if ($object->success == 1)
            {
                Push_notification_sent::whereId($this->id)->update(["status_id" => 2]);
            }
            else
            {
                Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
            }
        }
        else
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
    }

    public function sendIOS()
    {
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }
        // open connection 
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
         
        // send push
        $apple_cert = $this->device->application->pem_file->path();
        $message = json_encode([
            "aps" => [
                "alert" => [
                    "title" => $this->title,
                    "title" => $this->message,
                ],
                "sound" => "default",
                "badge" => 1
            ]
        ]);
        $token = $this->device->device_token;
        $http2_server = 'https://api.development.push.apple.com'; // or 'api.push.apple.com' if production
        $app_bundle_id = $this->device->application->server_key;
         
        $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
         
        // close connection
        curl_close($http2ch);

        if ($status == 200)
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 2, "response_code" => $status, "response_string" => $body]);
        }
        else
        {
            $object = json_decode($body);
            if ($status == 400 &&  $object->reason == "BadDeviceToken")
            {
                $device->enabled = 2;
                $device->save();
            }
            if ($response->getStatusCode() == 410)
            {
                $device->enabled = 3;
                $device->save();
            }
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3, "response_code" => $status, "response_string" => $body]);
        }
    }

    function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token)
    {
        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";
     
        // certificate
        $cert = realpath($apple_cert);
     
        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );
     
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
            CURLOPT_HEADER => 1
        ));
     
        // go...
        $result = curl_exec($http2ch);
        if ($result === FALSE) {
          throw new Exception("Curl failed: " .  curl_error($http2ch));
        }
     
        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
     
        return $status;
    }

    // relationship
    public function device()
    {
        return $this->belongsTo("App\Models\Push_device", "device_id");
    }
}