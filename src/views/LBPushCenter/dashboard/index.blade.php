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
                {{ trans("lbpushcenter.dashboard.title") }} 
            <span>> 
                {{ trans("lbpushcenter.dashboard.subtitle") }} 
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-sm-6">
            @box_open("Device by type")
                <div>
                    <div class="widget-body no-padding">
                        <div id="pie-chart" class="chart"></div>
                    </div>
                </div>
            @box_close
        </article>
    </div>
</section>

@endsection

@push('script')


<script src="/sa/js/plugin/flot/jquery.flot.cust.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.resize.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.orderBar.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.pie.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.time.min.js"></script>
<script src="/sa/js/plugin/flot/jquery.flot.tooltip.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        if ($('#pie-chart').length) {
            $.get("/lbpushcenter/ajax/application", function (data) {
                console.log(data);
                var data_pie = [];
                for (var i = 0; i < data.length; i ++)
                {
                    var application = data[i];
                    data_pie.push({
                        label: application.name + " (" + application.devices_count + " devices)",
                        data: application.devices_count
                    });
                }

                $.plot($("#pie-chart"), data_pie, {
                    series : {
                        pie : {
                            show : true,
                            innerRadius : 0.5,
                            radius : 1,
                            label : {
                                show : false,
                                radius : 2 / 3,
                                formatter : function(label, series) {
                                    return '<div style="font-size:11px;text-align:center;padding:4px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold : 0.1
                            }
                        }
                    },
                    legend : {
                        show : true,
                        noColumns : 1, // number of colums in legend table
                        labelFormatter : null, // fn: string -> string
                        labelBoxBorderColor : "#000", // border color for the little label boxes
                        container : null, // container (as jQuery object) to put legend in, null means default on top of graph
                        position : "ne", // position of default legend container within plot
                        margin : [5, 10], // distance from grid edge to default legend container within plot
                        backgroundColor : "#efefef", // null means auto-detect
                        backgroundOpacity : 1 // set to 0 to avoid background
                    },
                    grid : {
                        hoverable : true,
                        clickable : true
                    },
                });
            }, 'json');
        }
    });

</script>
@endpush