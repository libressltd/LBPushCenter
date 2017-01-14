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
                {{ trans("lbpushcenter.device.notification.create.title") }} 
            <span>> 
                {{ trans("lbpushcenter.device.notification.create.subtitle") }} 
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    {!! Form::open(array('url' => "lbpushcenter/device/$device->id/notification", 'method' => "POST", 'files' => true)) !!}
        <div class="row">
            <article class="col-lg-8 col-md-6">
                @box_open(trans("lbpushcenter.device.notification.create.title"))
                    <div>
                        <div class="widget-body">
                            <fieldset>
                                {!! Form::lbText("title", "", trans("lbpushcenter.device.notification.title.title")) !!}
                                {!! Form::lbText("description", "", trans("lbpushcenter.device.notification.description.title")) !!}
                            </fieldset>
                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('general.submit')}}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ trans('general.back')}}
                                </button>
                            </footer>
                        </div>
                    </div>
                @box_close
            </article>
        </div>
    {!! Form::close() !!}
</section>

@endsection
