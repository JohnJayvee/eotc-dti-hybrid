<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\Citizen;
use App\Laravel\Models\CitizenIdentification;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class CitizenTransformer extends TransformerAbstract{

	protected $availableIncludes = [
		'identification'
    ];


	public function transform(Citizen $citizen) {
		$with_identification = CitizenIdentification::where('citizen_id',$citizen->id)->whereIn('status',['approved','pending'])->first();

	    return [
	     	'user_id' => $citizen->id,
	     	'user_type' => $citizen->type?:"",
	     	'user_code' => $citizen->code?:"",
	     	'contact_number' => $citizen->contact_number?:"",
	     	'fname' => $citizen->fname?:"",
	     	'lname' => $citizen->lname?:"",
	     	'mname' => $citizen->mname?:"",
	     	'suffix' => $citizen->suffix?:"",
	     	'with_id' => $with_identification ? ($with_identification->status == "pending" ? "pending" : "yes") : "no",
	     	'birthdate' => $citizen->birthdate?$citizen->birthdate->format("Y-m-d"):"",
	     	'age' => $citizen->birthdate? $citizen->birthdate->diffInYears(Carbon::now())." y/o" : "n/a",
	     	'civil_status' => $citizen->civil_status?:"",
	     	'name' => $citizen->name?:"",
	     	'username' => $citizen->username?:"",
	     	'email' => $citizen->email?:"",
	     	'residence_address' => $citizen->residence_address?:"",
	     	'brgy' => $citizen->brgy?:"",
	     	'district' => $citizen->district?:"",


	     	'date_created' => [
	     		'date_db' => $citizen->created_at->format("Y-m-d"),
	     		'month_year' => $citizen->created_at->format("F Y"),
	     		'time_passed' => Helper::time_passed($citizen->created_at),
	     		'timestamp' => $citizen->created_at
	     	],
 	     	'last_login' => [
 				'date_db' => $citizen->date_db($citizen->last_login_at,env("MASTER_DB_DRIVER","mysql")),
 				'month_year' => $citizen->month_year($citizen->last_login_at),
 				'time_passed' => $citizen->time_passed($citizen->last_login_at),
 				'timestamp' => $citizen->last_login_at
 			],
 			'avatar' => [
 				'path' => $citizen->directory?:"",
 	 			'filename' => $citizen->filename?:"",
 	 			'path' => $citizen->path?:"",
 	 			'directory' => $citizen->directory?:"",
 	 			'full_path' => strlen($citizen->filename) > 0 ? "{$citizen->directory}/resized/{$citizen->filename}": "",
 	 			'thumb_path' => strlen($citizen->filename) > 0 ? "{$citizen->directory}/thumbnails/{$citizen->filename}": "",
 			],
	     ];
	}

	public function includeIdentification(Citizen $citizen){
		$identification =  CitizenIdentification::where('citizen_id',$citizen->id)->whereIn('status',['pending','approved'])->first();
		if(!$identification){
			$identification = new CitizenIdentification;
			$identification->id = 1;
			$identification->citizen_id = $citizen->id;
		}

		return $this->item($identification, new CitizenIdentificationTransformer);
	}
}