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
                {{ trans("lbpushcenter.application.list.title") }} 
            <span>> 
                {{ trans("lbpushcenter.application.list.subtitle") }} 
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
                    <h2>{{ trans("lbpushcenter.application.list.title") }} </h2>
                </header>
                <div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans("lbpushcenter.application.name.title") }}</th>
                                        <th>{{ trans("lbpushcenter.application.description.title") }}</th>
                                        <th>{{ trans("lbpushcenter.application.type.title") }}</th>
                                        <th>{{ trans("lbpushcenter.application.production_mode.title") }}</th>
                                        <th>{{ trans("general.action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                    <tr>
                                        <td>{{ $application->name }}</td>
                                        <td>{{ $application->description }}</td>
                                        <td>{{ $application->type->name }}</td>
                                        <td>{{ $application->production_mode }}</td>
                                        <td>
                                            <a href="{{ url("lbpushcenter/application/$application->id/edit") }}" class="btn btn-xs btn-primary">
                                                {{ trans('general.edit')}}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <footer>
                            <a href="{{ url('lbpushcenter/application/create') }}" class="btn btn-primary">
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
