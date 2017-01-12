<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class Push_application_type extends Model
{
    use Uuid32ModelTrait;

    static function all_to_option()
    {
    	$array = array();
    	foreach (Push_application_type::all() as $type)
    	{
    		$array[] = ["name" => $type->name, "value" => $type->id];
    	}
    	return $array;
    }
}
