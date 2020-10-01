<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ID - Print OUT</title>
	<style type="text/css">
		body{ font-size: 12px; }
		p{margin: 5px;}
		.text-center{ text-align: center; }
		.id-holder,.id-back-holder{ width: 100%; height: 220px; border: 2px solid #333; padding: 5px 7px; position: relative;}
		.id-back-holder .qr img{ position: absolute; left: 0px; top: 2px; }
		.heading p{ text-align: center; line-height: 10px;}
		.heading p.brgy{font-size: 14px; font-weight: 600;}
		.heading p.title{ font-size: 14px;  padding-top: 20px; letter-spacing: 1px; font-weight: 600; text-decoration: underline; }
		
		.resident-holder,.resident-emergency-holder {  height: 150px; clear: both; padding: 5px;}
		.resident-holder .picture{ height: inherit; float: left;  width: 30%;border: 0px solid #333; }
		.resident-holder .picture .avatar{ width: 100px; width: 100px; text-align: center; }
		.resident-holder .picture img{ height: 80px; width: 80px;  border: 1px solid #333; }
		.resident-holder .info,.resident-emergency-holder .info{ height: inherit;  float: left; width: 70%;border: 0px solid #333;}
		
		.resident-holder .info .number{ font-size: 14px; font-weight: 600; padding-left: 5px; }
		.resident-holder .info .name{ padding-left: 5px; }
		.resident-holder .info .bday{ padding-left: 5px; }
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
			<td width="50%">
				<div class="id-holder">
					<div class="heading">
						<p class="brgy">BRGY. {{$identification->residence_brgy}}</p>
						<p class="district">{{$identification->district == "north" ? "North" : "South"}} Cebu District</p>
						<p class="city">Cebu City, Cebu Philippines</p>

						<p class="title">IDENTIFICATION CARD</p>
					</div>
					<div class="resident-holder">
						<div class="picture">
							<div class="avatar">
								<img src="{{strlen($identification->avatar_filename) > 0 ? "{$identification->avatar_directory}/resized/{$identification->avatar_filename}" : asset('placeholder/user.png')}}" alt="">
							</div>
						</div>
						<div class="info">
							<p class="number">{{$identification->official_id}}</p>
							<p class="name">{{Str::upper($identification->lname)}}, {{Str::upper($identification->fname)}} {{Str::upper(substr($identification->mname,0,1))}}{{$identification->mname?".":NULL}}</p>
							<p class="bday">Birthdate: {{Helper::date_format($identification->birthdate,"F d, Y")}}</p>
							<p class="signature text-center">
								Signature
							</p>
						</div>
					</div>
				</div>
			</td>
			<td width="50%">
				<div class="id-back-holder">
					<div class="qr">
						<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate($identification->official_id)) !!}">
					</div>
					<div class="resident-emergency-holder">
						<div class="info">
							<p class="title">In case of emergency, please contact immediately:</p>
							<p class="name">Name: </p>
							<p class="number">Contact No.: </p>

						</div>
					</div>
					<div class="benefits-holder">
						<p>Benefits &amp; Priveleges:</p>
						<p>{{implode(", ",$benefits)}}</p>

					</div>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>