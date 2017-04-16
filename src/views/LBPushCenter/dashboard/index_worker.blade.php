@box_open("Statistic")
    <div ng-controller="LBPushcenterStatistic">
        <div class="widget-body">
        	<h6>Pending notification: @{{ info.pending }}</h6>
        	<p>Speed: @{{ info.speed }} n/s (base on last 5 seconds)</p>
        	<p>Speed: @{{ info.speed * 3.6 }} kn/s (base on last 30 seconds)</p>
        	<p>Left: @{{ info.pending / info.speed }} seconds</p>

        	<h6>Notification in 1 hours</h6>
        	<p>@{{ info.pending }} pending</p>
        	<p>@{{ info.success }} success</p>
        	<p>@{{ info.error }} error</p>
        	<p>@{{ info.opened }} opened</p>
        </div>
    </div>
@box_close

@box_open("Quick test")
    <div ng-controller="LBPushcenterStatistic">
        <div class="widget-body">
        	{!! Form::open(["url" => "/lbpushcenter/notification", "method" => "post"]) !!}
        	{!! Form::lbSelect("application_id", "", App\Models\Push_application::toOption("name", "id"), "Application") !!}
        	{!! Form::lbText("title", "", "Title") !!}
        	{!! Form::lbText("message", "", "Message") !!}
        	<div class="widget-footer">
	        	{!! Form::lbSubmit() !!}
        	</div>
        	{!! Form::close() !!}
        </div>
    </div>
@box_close

@box_open("Workers")
    <div>
        <div class="widget-body no-padding">
			<table class="table table-striped table-hover table-condensed">
				<thead>
					<tr>
						<th>Worker</th>
						<th>#pending</th>
						<th class="text-align-center">User Activity</th>
					</tr>
				</thead>
				<tbody ng-controller="LBPushcenterWorkerController">
					<tr ng-repeat="worker in workers" ng-cloak>
						<td>@{{ worker.id.substring(0, 5) }}</td>
						<td>@{{ worker.notifications_count }}</td>
						<td class="text-align-center hidden-xs">
							<span class="label label-warning" ng-if="worker.is_inactive">Inactive</span>
							<span class="label label-success" ng-if="! worker.is_inactive">Active</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@box_close

@push('script')
  
<script type="text/javascript">
	var bfapp = angular.module('LBPushcenterApp', []);

	bfapp.controller('LBPushcenterWorkerController', function($scope, $http) {

		function update() {
		    $http.get("/lbpushcenter/ajax/worker").then(function (response) {
		    	$scope.workers = response.data;
		    });
		}
        update();
        setInterval(update, 5000);
	});

	bfapp.controller('LBPushcenterStatistic', function($scope, $http) {
		function update() {
		    $http.get("/lbpushcenter/ajax/notification/static").then(function (response) {
		    	$scope.info = response.data;
		    });
		}
        update();
        setInterval(update, 5000);
	});

	bfapp.controller('LBPushcenterDeviceStatus', function($scope, $http) {
		function update() {
		    $http.get("/lbpushcenter/ajax/device/group_by_status").then(function (response) {
		    	$scope.info = response.data;
		    });
		}
        update();
        setInterval(update, 5000);
	});

</script>

@endpush
