<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;
use App\Models\Push_application;

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

    public function sendIOS()
    {
        $client = new GuzzleHttp\Client();
        $device_token = $this->device->device_token;
        $application = $this->device->application;
        $path;
        if ($application->production_mode)
        {
            $path = "https://api.development.push.apple.com/3/device/$device_token";
        }
        else
        {
            $path = "https://api.push.apple.com/3/device/$device_token";
        }
        $response = $client->request('POST', "path", [
            'json' => [
                'aps' => [
                    'alert' => $this->message
                ]
            ],
            'headers' => [
                'apns-topic' => $application->server_key
            ],
            'cert' => [
                $application->pem_file->path(),
                $application->pem_password
            ]
        ]);

        echo $response->getStatusCode();
        echo $response->getHeader('content-type');
        echo $response->getBody();
    }

    public function sendFCM()
    {
        
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