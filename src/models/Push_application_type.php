<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push_application_type extends Model
{

    static function all_to_option()
    {
    	$array = array();
    	foreach (Push_application_type::all() as $type)
    	{
    		$array[] = ["name" => $type->name, "value" => $type->id];
    	}
    	return $array;
    }

    static function init()
    {
        if (Push_application_type::count() > 0)
        {
            return;
        }
        $ios = new Push_application_type;
        $ios->name = "ios";
        $ios->description = "";
        $ios->color_class = "success";
        $ios->save();

        $ios = new Push_application_type;
        $ios->name = "android";
        $ios->description = "";
        $ios->color_class = "default";
        $ios->save();
    }
}
