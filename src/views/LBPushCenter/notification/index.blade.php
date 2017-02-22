@extends('app')

@section('sidebar_lbpushcenter')
active
@endsection

@section('sidebar_lbpushcenter_notification')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i> 
                {{ trans("lbpushcenter.notification.list.title") }} 
            <span>> 
                {{ trans("lbpushcenter.notification.list.subtitle") }} 
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-lg-12">
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>{{ trans("lbpushcenter.notification.list.title") }} </h2>
                </header>
                <div>
                    <div class="widget-body">
                        @include("layouts.elements.table", [
                            'url' => '/lbpushcenter/ajax/notification',
                            'columns' => [
                                ['data' => 'created_at', 'title' => trans("general.created_at")],
                                ['data' => 'device.id', 'title' => trans("lbpushcenter.notification.device.title")],
                                ['data' => 'title', 'title' => trans("lbpushcenter.notification.title.title")],
                                ['data' => 'message', 'title' => trans("lbpushcenter.notification.message.title")],
                                ['data' => 'status_id', 'title' => trans("lbpushcenter.notification.status.title")],
                                ['data' => 'device.application.type.name', 'title' => trans("lbpushcenter.notification.device_type.title")],
                            ]
                        ])
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

@endsection
