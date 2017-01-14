@extends('app')

@section('sidebar_lbpushcenter')
active
@endsection

@section('sidebar_lbpushcenter_application_type')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i> 
                {{ trans("lbpushcenter.application_type.list.title") }} 
            <span>> 
                {{ trans("lbpushcenter.application_type.list.subtitle") }} 
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
                    <h2>{{ trans("lbpushcenter.application_type.list.title") }} </h2>
                </header>
                <div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans("lbpushcenter.application_type.name.title") }}</th>
                                        <th>{{ trans("lbpushcenter.application_type.description.title") }}</th>
                                        <th>{{ trans("lbpushcenter.application_type.color_class.title") }}</th>
                                        <th>{{ trans("general.action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($types as $type)
                                    <tr>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->description }}</td>
                                        <td>{{ $type->color_class }}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <footer>
                            <a href="{{ url('lbpushcenter/application_type/create') }}" class="btn btn-primary">
                                {{ trans('general.add')}}
                            </a>
                            @if (App\Models\Push_application_type::count() == 0)
                            <a href="{{ url('lbpushcenter/application_type/init') }}" class="btn btn-primary">
                                {{ trans('lbpushcenter.application_type.init.title')}}
                            </a>
                            @endif
                        </footer>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

@endsection
