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
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans("lbpushcenter.notification.id.title") }}</th>
                                        <th>{{ trans("lbpushcenter.notification.device.title") }}</th>
                                        <th>{{ trans("lbpushcenter.notification.title.title") }}</th>
                                        <th>{{ trans("lbpushcenter.notification.message.title") }}</th>
                                        <th>{{ trans("lbpushcenter.notification.status.title") }}</th>
                                        <th>{{ trans("lbpushcenter.notification.device_type.title") }}</th>
                                        <th>{{ trans("general.created_at") }}</th>
                                        <th>{{ trans("general.action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ $notification->device->id }}</td>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ $notification->message }}</td>
                                        <td>{{ $notification->status_id }}</td>
                                        <td>{{ $notification->device->application->type->name }}</td>
                                        <td>{{ $notification->created_at }}</td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

@endsection
