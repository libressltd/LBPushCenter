<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;
use App\Models\Push_application;
use GuzzleHttp\Client;

class Push_notification_sent extends Model
{
    use Uuid32ModelTrait, LBDatatableTrait;
    protected $fillable = ['status_id'];

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