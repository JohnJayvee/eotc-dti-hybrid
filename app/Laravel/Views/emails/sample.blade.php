<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Application from </title>

	<style>
		th.primary{
			background: url('{{asset('web/img/plain_bg.jpg')}}') no-repeat center ; 
		}
		table, th, td {
		  border-collapse: collapse;
		  padding-left: 20px;
		  padding-right: 20px;
		}

		table.center {
			margin-left:auto; 
			margin-right:auto;
			border-bottom: solid 1px #F0F0F0;
			border-right: solid 1px #F0F0F0;
			border-left: solid 1px #F0F0F0;
		}
		.text-white{
			color:#fff;
		}
		.bold{
			font-weight: bolder;
		}
		.text-blue{
			color: #27437D;
		}
		.text-gray{
			color: #848484;
		}
		.bg-white{
			background-color: #fff;
		}
		hr.new2 {
		  border-top: 3px dashed #848484;
		  border-bottom: none;
		  border-left: none;
		  border-right: none;
		}
		#pageElement{display:flex; flex-wrap: nowrap; align-items: center}
	</style>
</head>
<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;  font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; margin: 0;">
	<table class="center bg-white" width="55%">
		<tr>
			<th colspan="2" class="primary" style="padding: 30px;">
				<div id="pageElement">
					<div style="float: left;color: #fff;padding-left: 30px;">Thank You for using &nbsp;</div>
				  	<div style="padding-right: 30px;"> <img src="{{asset('web/img/logo-color.png')}}" alt="" style="width: 130px;"> </div>
				</div>
			</th>
		</tr>
		<tr>
			<th colspan="2" class="text-gray" style="padding: 10px;">Date: July 15, 2020 | 10:00 am</th>
		</tr>
		<tr class="text-blue">
			<th style="text-align: left;padding: 10px;">Applicant:</th>
			<th style="text-align: right;">Crisostomo Ibarra</th>
		</tr>
		<tr class="text-blue">
			<th style="text-align: left;padding: 10px;">Company Name:</th>
			<th style="text-align: right;">El Fili Inc</th>
		</tr>
		<tr class="text-blue">
			<th style="text-align: left;padding: 10px;">Department:</th>
			<th style="text-align: right;">Admin Department</th>
		</tr>
		<tr class="text-blue">
			<th style="text-align: left;padding: 10px;">Applying For:</th>
			<th style="text-align: right;">Application Purpose</th>
		</tr>
		<tr>
			<th colspan="2" style="border: none;padding-top: 20px;"><hr class="new2"></th>
		</tr>
		<tr>
			<th colspan="2">
				<img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(200)->generate('Make me into an QrCode!')) }} ">
			</th>
		</tr>
		<tr>
			<th colspan="2"><span style="color: #DE9924;font-size: 17px;letter-spacing: 1px;">#PAY-0000001</span> <br><p class="text-gray"style="margin-top: 0px;">Reference Code</p></th>
		</tr>
	</table>
	
</body>
</html>