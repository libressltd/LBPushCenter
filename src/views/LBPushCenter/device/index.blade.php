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
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans("lbpushcenter.device.id.title") }}</th>
                                        <th>{{ trans("lbpushcenter.device.token.title") }}</th>
                                        <th>{{ trans("lbpushcenter.device.application.title") }}</th>
                                        <th>{{ trans("lbpushcenter.device.type.title") }}</th>
                                        <th>{{ trans("lbpushcenter.device.unread.title") }}</th>
                                        <th>{{ trans("lbpushcenter.device.users.title") }}</th>
                                        <th>{{ trans("general.action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($devices as $device)
                                    <tr>
                                        <td>{{ $device->id }}</td>
                                        <td>{{ $device->device_token }}</td>
                                        <td>{{ $device->application->name }}</td>
                                        <td>{{ $device->application->type->name }}</td>
                                        <td>{{ $device->badge() }}</td>
                                        <td>
                                            @foreach ($device->users as $user)
                                                {{ $user->name }} 
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ url("lbpushcenter/device/$device->id/notification/create") }}" class="btn btn-primary">
                                                {{ trans('lbpushcenter.device.notification.title')}}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <footer>
                            <a href="{{ url('lbpushcenter/application_type/create') }}" class="btn btn-primary">
                                {{ trans('general.add')}}
                            </a>
                        </footer>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

@endsection
