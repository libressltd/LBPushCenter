@extends('app')

@section('sidebar_lbpushcenter')
active
@endsection

@section('sidebar_lbpushcenter_device')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i> 
                {{ trans("lbpushcenter.device.list.title") }} 
            <span>> 
                {{ trans("lbpushcenter.device.list.subtitle") }} 
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
                    <h2>{{ trans("lbpushcenter.device.list.title") }} </h2>
                </header>
                <div>
                    <div class="widget-body">
                        @include("layouts.elements.table", [
                            'url' => '/lbpushcenter/ajax/device',
                            'columns' => [
                                ['data' => 'id', 'title' => trans("lbpushcenter.device.id.title")],
                                ['data' => 'device_token', 'title' => trans("lbpushcenter.device.token.title")],
                                ['data' => 'application.name', 'title' => trans("lbpushcenter.device.application.title")],
                                ['data' => 'application.type.name', 'title' => trans("lbpushcenter.device.type.title")],
                                ['data' => 'badge', 'title' => trans("lbpushcenter.device.unread.title")],
                                ['data' => 'users', 'title' => trans("lbpushcenter.device.users.title")],
                                ['data' => 'notification_button', 'title' => trans("lbpushcenter.device.notification.title")],
                            ]
                        ])
                        <div class="widget-footer">
                            <a href="{{ url("lbpushcenter/device/all/notification/create") }}" class="btn btn-primary">
                                {{ trans('lbpushcenter.device.notification.sendtoall.title')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

@endsection
