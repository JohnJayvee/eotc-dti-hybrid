<!DOCTYPE html>
<html>
<head>
	<title>EOTC Certificate</title>
</head>
<style type="text/css">
	.text-center{
		text-align: center;
	}
</style>
<body>
<table style="width:100%;margin-bottom: 2em;">
  <tr class="text-center">
    <td >
		<img src="{{ public_path('web/img/dti-logo.png') }}" style="width: 10em;">
    </td>
    
   </tr>
</table>
<div class="text-center">
	<p style="font-size: 20px;">This certifies that</p>
	<p style="font-size: 25px;font-weight: bold;">{{$transaction->customer ? $transaction->customer->full_name : $transaction->customer_name}}</p>
	<p style="font-size: 20px;">has successfully completed the application in DTI EOTC-PHP with the following details:</p>
	<p style="font-size: 25px;font-weight: bold;">{{$transaction->application_name}}</p>
	<p style="font-size: 25px;font-weight: bold;">{{$transaction->department_name}}</p>
	<p style="font-size: 25px;font-weight: bold;">{{Helper::date_only($transaction->modified_at)}}</p>
	<p style="font-size: 20px;">In testimony whereof, I hereby sign this Digital Certificate</p>
	<p style="font-size: 20px;">and issue the same on {{Helper::date_only($transaction->modified_at)}} in the Philippines.</p>
	
</div>
<div class="text-center" style="margin-top: 4em">
	<p style="font-size: 20px;">Juan Dela Cruz</p>
	<p style="font-size: 20px;">Department Head</p>
	<p><img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(180)->generate('Make me into an QrCode!')) }} " style="padding: 0;"></p>
	<p style="padding-top: -30px">MKUG608210053961</p>
</div>
<p>Documentary Stamp Tax Paid Php 30.00</p>
</body>
</html>