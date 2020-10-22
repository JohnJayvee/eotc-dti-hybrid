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
				<td>Bureau/Office</td>
				<td>Processing Fee</td>
				<td>Processing Fee Status</td>
				<td>Application Amount</td>
				<td>Application Status</td>
				<td>Processor</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody>
			@forelse($transactions as $value)
				<tr align="center">
					<td>{{Helper::date_format($value->created_at)}}</td>
					<td>{{$value->customer_name}}</td>
					<td>{{$value->company_name}}</td>
					<td>{{$value->department->name}}</td>
					<td>{{Helper::money_format($value->processing_fee)}}</td>
					<td>{{$value->payment_status}}</td>
					<td>{{Helper::money_format($value->amount) ?: '---'}}</td>
					<td>{{$value->application_payment_status}}</td>
					<td>{{ $value->admin ? $value->admin->full_name : '---' }}</td>
					<td>{{ $value->is_resent == 1 ? "RESENT" : $value->status}}</td>
				</tr>
			@empty
			@endforelse
		</tbody>
	</table>
</body>
</html>