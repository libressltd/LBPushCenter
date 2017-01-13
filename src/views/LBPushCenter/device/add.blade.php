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
                {{ trans("lbpushcenter.application_type.create.title") }} 
            <span>> 
                {{ trans("lbpushcenter.application_type.create.subtitle") }} 
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    @if (isset($type))
    {!! Form::open(array('url' => "lbpushcenter/application_type/$type->id", 'method' => "put", 'files' => true)) !!}
    @else
    {!! Form::open(array('url' => "lbpushcenter/application_type", 'method' => "post", 'files' => true)) !!}
    @endif
        <div class="row">
            <article class="col-lg-8 col-md-6">
                @box_open(trans("lbpushcenter/application_type.create.title"))
                    <div>
                        <div class="widget-body">
                            <fieldset>
                                {!! Form::lbText("name", @$type->name, trans("lbpushcenter.application_type.name.title")) !!}
                                {!! Form::lbText("description", @$type->description, trans("lbpushcenter.application_type.description.title")) !!}
                                {!! Form::lbText("color_class", @$type->type_id, trans("lbpushcenter.application_type.color_class.title")) !!}
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
