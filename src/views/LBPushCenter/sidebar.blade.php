@include("layouts.elements.sidebar_item_multi_open", ["title" => "Push Center", "icon" => "fa fa-user ", "id" => "sidebar_lbpushcenter"])
@include("layouts.elements.sidebar_item_single", ["title" => "Application", "icon" => "fa fa-user ", "url" => "/lbpushcenter/application", "id" => "sidebar_lbpushcenter_application"])
@include("layouts.elements.sidebar_item_single", ["title" => "Application Type", "icon" => "fa fa-user ", "url" => "/lbpushcenter/application_type", "id" => "sidebar_lbpushcenter_application_type"])
@include("layouts.elements.sidebar_item_multi_close")