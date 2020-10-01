<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\TravelHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class TravelHistoryTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];


	public function transform(TravelHistory $travel) {

	    return [
	     	'travel_id' => $travel->id,
	     	'officer_id' => $travel->officer_id?:"",
	     	'officer_name' =>  $travel->officer?$travel->officer->name:"-",
	     	// 'citizen_id' => $travel->citizen_id?:"",
	     	'travel_code' => $travel->code?:"",
	     	'contact_number' => $travel->contact_number?:"",
	     	// 'fname' => $travel->fname?:"",
	     	// 'lname' => $travel->lname?:"",
	     	// 'fname' => $travel->fname?:"",
	     	// 'lname' => $travel->lname?:"",
	     	// 'age' => $travel->age?:"",
	     	// 'name' => $travel->name?:"",
	     	// 'status' => $travel->status?:"",
	     	// 'type' => $travel->type?:"",
	     	'geo_lat' => $travel->geo_lat?:"",
	     	'geo_long' => $travel->geo_long?:"",
	     	'location' => $travel->location?:"",
	     	// 'district' => $travel->district?:"",
	     	// 'residence_address' => $travel->residence_address?:"",
	     	// 'reason' => [
	     	// 	"food_medicine" => $travel->reason_food_medicine?:"no",
	     	// 	"hospital" => $travel->reason_hospital?:"no",
	     	// 	"bank" => $travel->reason_bank?:"no",
	     	// 	"other" => $travel->reason_other?:"",
	     	// ],
	     	'travel_at' => [
	     		'date_db' => $travel->created_at->format("Y-m-d"),
	     		'month_year' => $travel->created_at->format("F Y"),
	     		'time_passed' => Helper::time_passed($travel->created_at),
	     		'timestamp' => $travel->created_at
	     	],
 	     	
	     ];
	}
}