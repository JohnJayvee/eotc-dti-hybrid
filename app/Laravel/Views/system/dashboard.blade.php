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
			<div class="card h-100" style="border: none;border-radius: 10px;">
				<div class="card-body">
					<h5 class="text-title d-inline-block p-3">Monthly Summary</h5>
					<div id="bar-example" ></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 p-2">
			<div class="card h-100" style="border: none;border-radius: 10px;">
				<div class="card-body">
					<h5 class="text-title d-inline-block p-3">Total Transactions</h5>
					<div class="row">
						<div class="col-md-12">
							<canvas id='myChart' height="180"></canvas>
						</div>
						<div class="col-md-12">
							<div id="js-legend" class="chart-legend"></div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
	@if(Auth::user()->type != "processor")
	<div class="row">
		<div class="col-md-6">
			<div class="card h-100" style="border: none;border-radius: 10px;">
				<div class="card-body">
					<table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;">
						<thead>
							<tr>
								<th width="25%" class="text-title p-3">Application Name</th>
								<th width="25%" class="text-title p-3">Amount</th>
							</tr>
						</thead>
						<tbody>
							@foreach($amount_per_application as $index)
							<tr>
								<td>{{$index->name}}</td>
								<td>PHP {{Helper::money_format($index->amount_sum ?: "0.00")}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<h5 class="p-2">Total Amount Collected : PHP {{Helper::money_format($total_amount->total ?: "0.00")}}</h5>
				</div>

			</div>
		</div>
	</div>
	@endif
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
	  padding-top: 3em;
}
.chart-legend li{
	display: inline;
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
        labels: {!! $label_data !!},
        datasets: [{
            data: {!! $chart_data !!},
            backgroundColor: ["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"],
            borderWidth: 0.5 ,
            borderColor: '#ddd'
        }]
    },
    options: {
    	responsive: true,
       	legend: {
            display: false,
            position: 'right',
            labels: {
                boxWidth: 30,
                fontColor: '#111',
                padding: 30
            },
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