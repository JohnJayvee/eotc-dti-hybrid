<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="0" border="1">
		<thead>
			<tr align="center">
				<td>Transaction Date</td>
				<td>Submitted By</td>
				<td>Company Name</td>
				<td>Department Name</td>
				<td>Processing Fee</td>
				<td>Processing Fee Status</td>
				<td>Application Amount</td>
				<td>Application Status</td>
				<td>Processor</td>
			</tr>
		</thead>
		<tbody>
			@forelse($transactions as $value)
				<tr align="center">
					<td>{{Helper::date_format($value->created_at)}}</td>
					<td>{{$value->customer->full_name}}</td>
					<td>{{$value->company_name}}</td>
					<td>{{$value->department->name}}</td>
					<td>{{Helper::money_format($value->processing_fee)}}</td>
					<td>{{$value->payment_status}}</td>
					<td>{{Helper::money_format($value->amount) ?: '---'}}</td>
					<td>{{$value->application_payment_status}}</td>
					<td>{{ $value->admin ? $value->admin->full_name : '---' }}</td>
				</tr>
			@empty
			@endforelse
		</tbody>
	</table>
</body>
</html>