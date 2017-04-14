@box_open("Number of notification left")
    <div>
        <div class="widget-body no-padding">
			<table class="table table-striped table-hover table-condensed" ng-app="LBPushcenterApp">
				<thead>
					<tr>
						<th>Worker</th>
						<th>#pending</th>
						<!-- <th class="text-align-center">User Activity</th>
						<th class="text-align-center hidden-xs">Online</th>
						<th class="text-align-center">Demographic</th> -->
					</tr>
				</thead>
				<tbody ng-controller="LBPushcenterWorkerController">
					<tr ng-repeat="worker in workers">
						<td><a href="javascript:void(0);">@{{ worker.id }}</a></td>
						<td>@{{ worker.notifications_count }}</td>
						<!-- <td class="text-align-center">
						<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
							2700, 3631, 2471, 1300, 1877, 2500, 2577, 2700, 3631, 2471, 2000, 2100, 3000
						</div></td>
						<td class="text-align-center hidden-xs">143</td>
						<td class="text-align-center">
						<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
							17,83
						</div>
						<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
							<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-cog fa-lg"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-xs pull-right">
								<li>
									<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
								</li>
								<li>
									<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
								</li>
								<li class="divider"></li>
								<li class="text-align-center">
									<a href="javascript:void(0);">Cancel</a>
								</li>
							</ul>
						</div>
						</td> -->
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@box_close

@push('script')
<script>
    if (!window.angular) {
      document.write('<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"><\/script>');
    }
</script>
  
<script type="text/javascript">

    $(document).ready(function() {
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
	});
</script>
@endpush
