@extends('system._layouts.main')

@section('content')
<div class="row">
	<div class="col-12">
        @include('system._components.notifications')
	</div>
</div>
<div class="container p-4">
	<h5 class="text-title text-uppercase">Dashboard</h5>
	<p class="text-sub-title fw-500 pt-2">Today's Daily Summary</p>
	<div class="row">
	    <div class="col-md-3">
	      <div class="card-counter info">
	        <i class="fa fa-hourglass-half"></i>
	        <span class="count-numbers">{{$pending}}</span>
	        <span class="count-name">Pending Applications</span>
	      </div>
	    </div>
	    <div class="col-md-3">
	      <div class="card-counter success">
	        <i class="fa  fa-check-circle"></i>
	        <span class="count-numbers">{{$approved}}</span>
	        <span class="count-name">Approved Applications</span>
	      </div>
	    </div>
	    <div class="col-md-3">
	      <div class="card-counter primary">
	        <i class="fa fa-file"></i>
	        <span class="count-numbers">{{$application_today}}</span>
	        <span class="count-name">Total Daily Applications</span>
	      </div>
	    </div>
	    <div class="col-md-3">
	      <div class="card-counter danger">
	        <i class="fa fa-times-circle"></i>
	        <span class="count-numbers">{{$declined}}</span>
	        <span class="count-name">Disapproved Applications</span>
	      </div>
	    </div>
	</div>
	<div class="row pt-2">
		<div class="col-md-6 p-2">
			<div class="card" style="border: none;border-radius: 10px;">
				<div class="card-body">
					<h5 class="text-title d-inline-block p-3">Monthly Summary</h5>
					<div id="bar-example" ></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 p-2">
			<div class="card" style="border: none;border-radius: 10px;">
				<div class="card-body">
					<h5 class="text-title d-inline-block p-3">Current Average</h5>
					<div class="row pl-4">
						<div class="col-md-12">
							<canvas id='myChart' height="210"></canvas>
						</div>
						<!-- <div class="col-md-12">
							<div id="js-legend" class="chart-legend"></div>
						</div>	 -->
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
@stop 

@section('page-styles')
<link rel="stylesheet" href="{{asset('system/vendors/c3/c3.min.css')}}">
<link rel="stylesheet" href="{{asset('system/vendors/morris.js/morris.css')}}">
<style type="text/css">
	
.chart-legend li span{
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 5px;
    vertical-align: middle;
  	
}
.chart-legend ul{
	list-style: none;
}
.chart-legend li{
	padding: 5px;
}
.chart-legend{
	  padding-top: 7em;
}
</style>
@stop

@section('page-scripts')
<script src="{{asset('system/vendors/raphael/raphael.min.js')}}"></script>
<script src="{{asset('system/vendors/morris.js/morris.min.js')}}"></script>
<script src="{{asset('system/vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('system/vendors/chart.js/chart-js-plugins-labels.js')}}"></script>
<script src="{{asset('system/vendors/d3/d3.min.js')}}"></script>
<script src="{{asset('system/vendors/c3/c3.js')}}"></script>
<script src="{{asset('system/vendors/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
<script src="{{asset('system/vendors/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{asset('system/vendors/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('system/vendors/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{asset('system/js/jquery.flot.dashes.js')}}"></script>
<script type="text/javascript">
	Morris.Bar({
		element: 'bar-example',
		data: {!! $per_month_application !!},
		xkey: 'month',
  		ykeys: ['approved', 'declined'],
		labels: ['Approved', 'declined'],
		barColors: ["#0045A2", "#D63231"],
		stacked: true,
		hideHover:'auto',
	});

	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! $department_data !!},
        datasets: [{
            data: {!! $chart_data !!},
            backgroundColor: ['#2F9A2A', '#D63231', '#F2B33D'],
            borderWidth: 0.5 ,
            borderColor: '#ddd'
        }]
    },
    options: {
    	responsive: true,
       	legend: {
            position: 'top',
            labels: {
                fontColor: "black",
                boxWidth: 20,
                padding: 20
            }
        },
        tooltips: {
            enabled: false
        },
        plugins:{
			labels: {
				render: 'value',
				fontColor: '#fff',
				fontSize:25
				
			}
		}
    }
});
document.getElementById('js-legend').innerHTML = myChart.generateLegend();
</script>
@stop