@extends('app')

@section('sidebar_lbpushcenter')
active
@endsection

@section('sidebar_lbpushcenter_application')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i> 
                {{ trans("lbpushcenter.application.create.title") }} 
            <span>> 
                {{ trans("lbpushcenter.application.create.subtitle") }} 
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    @if (isset($application))
    {!! Form::open(array('url' => "lbpushcenter/application/$application->id", 'method' => "put", 'files' => true)) !!}
    @else
    {!! Form::open(array('url' => "lbpushcenter/application", 'method' => "post", 'files' => true)) !!}
    @endif
        <div class="row">
            <article class="col-lg-8 col-md-6">
                @box_open(trans("lbpushcenter/application.create.title"))
                    <div>
                        <div class="widget-body">
                            <fieldset>
                                {!! Form::lbText("name", @$application->name, trans("lbpushcenter.application.name.title")) !!}
                                {!! Form::lbText("description", @$application->description, trans("lbpushcenter.application.description.title")) !!}
                                {!! Form::lbSelect("type_id", @$application->type_id, App\Models\Push_application_type::all_to_option(), trans("lbpushcenter.application.type.title")) !!}
                                {!! Form::lbText("server_key", @$application->server_key, trans("lbpushcenter.application.server_key.title")) !!}
                                {!! Form::lbText("server_secret", @$application->server_secret, trans("lbpushcenter.application.server_secret.title")) !!}
                                {!! Form::lbText("pem_password", @$application->pem_password, trans("lbpushcenter.application.pem_password.title")) !!}
                                {!! Form::label(trans("lbpushcenter.application.pem_file.title")) !!}
                                {!! Form::file("pem_file") !!}
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
