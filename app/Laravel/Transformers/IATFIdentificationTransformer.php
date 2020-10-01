<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\IATFIdentification;
use App\Laravel\Models\TravelHistory;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class IATFIdentificationTransformer extends TransformerAbstract{

	protected $availableIncludes = [
		'recent_history'
    ];


	public function transform(IATFIdentification $identification) {
	    return [
	     	'id_no' => $identification->IATF_ISSUE_ID_NO,
	     	'fname' => $identification->FIRST_NAME,
	     	'mname' => $identification->MIDDLE_NAME,
	     	'lname' => $identification->LAST_NAME,
	     	'suffix' => $identification->SUFFIX,
	     	'designation' => $identification->DESIGNATION,
	     	'sector' => $identification->SECTOR,
	     	'company1' => Str::upper($identification->CO_NAME_CLEAN1) == "UNKNOWN" ? "-" :$identification->CO_NAME_CLEAN1,
	     	'company2' => Str::upper($identification->CO_NAME_CLEAN2) == "UNKNOWN" ? "-" :$identification->CO_NAME_CLEAN2,
	     ];
	}

	public function includeRecentHistory(IATFIdentification $identification){
		$recent_history = TravelHistory::where('issued_id_no',$identification->IATF_ISSUE_ID_NO)->orderBy('created_at',"DESC")->take(5)->get();
       return $this->collection($recent_history, new TravelHistoryTransformer);

	}
}