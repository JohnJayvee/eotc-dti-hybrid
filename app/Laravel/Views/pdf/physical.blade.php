<!DOCTYPE html>
<html>
<head>
	<title>Document Reference Number</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<style type="text/css">
	.text-center{
		text-align: center;
	}
	.text-bold{
		font-weight: bold;
	}
</style>
<body>
<table width="100%" style="margin-bottom: 5em;">
	<tr class="text-center">
		<td style="background-color: #8FAADC;font-size: 25px;font-weight: bold;padding: .5em;border: solid 1px black;">DOCUMENT REFERENCE NUMBER</td>
	</tr>
</table>
<table width="100%">
	<tr >
		<td style="border: dashed #0070C0;padding: 1.5em">
			<p class="text-bold" style="font-size: 25px;">Reference #: {{$transaction->code}}</p><br><br><br>
			<p style="font-size: 25px;"><b>Application Name:</b> {{Str::title($transaction->application_name)}}</p>
			<p style="font-size: 25px;"><b>Department Name:</b> {{Str::title($transaction->department_name)}}</p>
			<p style="font-size: 25px;"><b>Date Generated:</b> {{Helper::date_only($transaction->created_at)}}</p>
		
		</td>
	</tr>
</table>

<p style="font-size: 25px;margin-top: 2em;">Please print this Document Reference Number and attach this to your physical documents/requirements and send this document to our office to proceed with your application.</p>
<p style="font-size: 25px;">Thank you for choosing DTI EOTC-PHP!</p>
</body>
</html>