<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\CitizenIdentification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class CitizenIdentificationTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];

	public function transform(CitizenIdentification $identification) {

	    return [
	     	'id' => $identification->id,
	     	'citizen_id' => $identification->citizen_id?:"",
	     	'official_id' => $identification->status == "approved" ? $identification->official_id : "-",
	     	'contact_number' => $identification->contact_number?:"",
	     	'fname' => $identification->fname?:"",
	     	'lname' => $identification->lname?:"",
	     	'mname' => $identification->mname?:"",
	     	'suffix' => $identification->suffix?:"",
	     	'birthdate' => $identification->birthdate?$identification->birthdate->format("Y-m-d"):"",
	     	'age' => $identification->birthdate? $identification->birthdate->diffInYears(Carbon::now())." y/o" : "n/a",
	     	'civil_status' => $identification->civil_status?:"",
	     	'name' => $identification->name?:"",
	     	'username' => $identification->username?:"",
	     	'email' => $identification->email?:"",
	     	'residence_street' => $identification->residence_street?:"",
	     	'residence_brgy' => $identification->residence_brgy?:"",
	     	'residence_district' => $identification->residence_district?:"",
	     	'permanent_street' => $identification->permanent_street?:"",
	     	'permanent_brgy' => $identification->permanent_brgy?:"",
	     	'permanent_city' => $identification->permanent_city?:"",
	     	'permanent_province' => $identification->permanent_province?:"",

	     	'date_created' => [
	     		'date_db' => isset($identification->created_at)?$identification->created_at->format("Y-m-d"):"",
	     		'month_year' => isset($identification->created_at)?$identification->created_at->format("F Y"):"",
	     		'time_passed' => isset($identification->created_at)?Helper::time_passed($identification->created_at):"",
	     		'timestamp' => isset($identification->created_at)?$identification->created_at:""
	     	],
	     	'document' => [
 				'path' => $identification->directory?:"",
 	 			'filename' => $identification->filename?:"",
 	 			'path' => $identification->path?:"",
 	 			'directory' => $identification->directory?:"",
 	 			'full_path' => strlen($identification->filename) > 0 ? "{$identification->directory}/resized/{$identification->filename}": "",
 	 			'thumb_path' => strlen($identification->filename) > 0 ? "{$identification->directory}/thumbnails/{$identification->filename}": "",
 			],
 			'avatar' => [
 				'path' => $identification->avatar_directory?:"",
 	 			'filename' => $identification->avatar_filename?:"",
 	 			'path' => $identification->avatar_path?:"",
 	 			'directory' => $identification->avatar_directory?:"",
 	 			'full_path' => strlen($identification->avatar_filename) > 0 ? "{$identification->avatar_directory}/resized/{$identification->avatar_filename}": "",
 	 			'thumb_path' => strlen($identification->avatar_filename) > 0 ? "{$identification->avatar_directory}/thumbnails/{$identification->avatar_filename}": "",
 			],
	     ];
	}
}