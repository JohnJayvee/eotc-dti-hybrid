<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Travel PASS - Print OUT</title>
	<style type="text/css">
		body{ font-size: 12px; }
		p{margin: 5px;}
		.text-center{ text-align: center !important; }
		.id-holder,.id-back-holder{ width: 100%; height: 220px; border: 2px solid #333; padding: 5px 7px; position: relative;}
		.id-holder .qr img{ position: absolute; right: 0px; top: 25px; }
		.heading p{ text-align: left; line-height: 10px;}
		.heading p.brgy{font-size: 14px; font-weight: 600;  margin-top: 20px;}
		.heading p.title{ font-size: 16px;  padding-top: 10px;  padding-bottom: 5px; letter-spacing: 1px; font-weight: 600; background: #df1234;  color: #fff; }
		
		.resident-holder,.resident-emergency-holder {  height: 150px; clear: both; padding: 5px;}
		.resident-holder .picture{ height: inherit; float: left;  width: 30%;border: 0px solid #333; }
		.resident-holder .picture .avatar{ width: 100px; width: 100px; text-align: center; }
		.resident-holder .picture img{ height: 80px; width: 80px;  border: 1px solid #333; }
		.resident-holder .info,.resident-emergency-holder .info{ height: inherit;  float: left; width: 70%;border: 0px solid #333;}
		
		.resident-holder .info .number{ font-size: 14px; font-weight: 600; padding-left: 5px; }
		.resident-holder .info .name{ padding-left: 5px; }
		.resident-holder .info .address{ padding-left: 5px; }
		.resident-holder .info .signature{ padding-left: 5px; margin-top: 40px; border-top: 1px  solid #333;}

		.resident-emergency-holder .info{
			margin-left: 40%; padding-top: 8px;
		}
		.id-back-holder .benefits-holder{ padding-left: 10px; }
		.resident-emergency-holder .info .name{ margin-bottom: 20px; }
		.resident-emergency-holder .info .number{ margin-bottom: 10px; }


		tr.row td{ padding: 5px; }
	</style>
</head>
<body>
	<table cellpadding="0" cellspacing="0" width="100%" border="0">\
		<tr class="row">
			<td width="60%">
				<div class="id-holder">
					<div class="qr">
						<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(120)->generate($citizen->code)) !!}">
					</div>
					<div class="heading">
						<p class="title text-center">TRAVEL PASS</p>
						<p class="brgy">BRGY. {{$citizen->brgy}}</p>
						<p class="district">{{$citizen->district == "north" ? "North" : "South"}} Cebu District</p>
						<p class="city">Cebu City, Cebu Philippines</p>

					</div>
					<div class="resident-holder">
						<div class="picture">
							<div class="avatar">
								<img src="{{strlen($citizen->filename) > 0 ? "{$citizen->directory}/resized/{$citizen->filename}" : asset('placeholder/user.png')}}" alt="">
							</div>
						</div>
						<div class="info">
							<p class="number">RefID: {{$citizen->code}}</p>
							<p class="name">{{Str::upper($citizen->lname)}}, {{Str::upper($citizen->fname)}} {{Str::upper(substr($citizen->mname,0,1))}}{{$citizen->mname?".":NULL}}</p>
							<p class="address">Address: {{$citizen->residence_address}}</p>
							{{-- <p class="bday">Birthdate: {{Helper::date_format($citizen->birthdate,"F d, Y")}}</p> --}}
							<p class="signature text-center">
								Approved By
							</p>
						</div>
					</div>
				</div>
			</td>
			<td width="40%">
				
			</td>
		</tr>
	</table>
</body>
</html>