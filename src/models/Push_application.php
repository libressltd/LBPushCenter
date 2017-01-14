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
}
