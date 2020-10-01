<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\User as Officer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class OfficerTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];


	public function transform(Officer $officer) {

	    return [
	     	'user_id' => $officer->id,
	     	'title' => $officer->title?:"",
	     	
	     	'fname' => $officer->fname?:"",
	     	'lname' => $officer->lname?:"",
	     	'mname' => $officer->mname?:"",
	     	'suffix' => $officer->suffix?:"",
	     	'name' => $officer->name?:"",
	     	'username' => $officer->username?:"",
	     	'contact_number' => $officer->contact_number?:"",

	     	'email' => $officer->email?:"",
	     	'job_position' => $officer->position?:"",


	     	'date_created' => [
	     		'date_db' => $officer->created_at->format("Y-m-d"),
	     		'month_year' => $officer->created_at->format("F Y"),
	     		'time_passed' => Helper::time_passed($officer->created_at),
	     		'timestamp' => $officer->created_at
	     	],

 	     	'last_login' => [
 				'date_db' => $officer->date_db($officer->last_login_at,env("MASTER_DB_DRIVER","mysql")),
 				'month_year' => $officer->month_year($officer->last_login_at),
 				'time_passed' => $officer->time_passed($officer->last_login_at),
 				'timestamp' => $officer->last_login_at
 			],
 			'avatar' => [
 				'path' => $officer->directory?:"",
 	 			'filename' => $officer->filename?:"",
 	 			'path' => $officer->path?:"",
 	 			'directory' => $officer->directory?:"",
 	 			'full_path' => strlen($officer->filename) > 0 ? "{$officer->directory}/resized/{$officer->filename}": "",
 	 			'thumb_path' => strlen($officer->filename) > 0 ? "{$officer->directory}/thumbnails/{$officer->filename}": "",
 			],
	     ];
	}
}