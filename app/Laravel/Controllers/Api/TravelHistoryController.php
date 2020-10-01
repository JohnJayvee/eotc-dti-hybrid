<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\TravelHistoryRequest;

/* Models
 */
use App\Laravel\Models\{Citizen,User,TravelHistory,Barangay};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,CitizenTransformer,OfficerTransformer,TravelHistoryTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class TravelHistoryController extends Controller{
	protected $response = [];
	protected $response_code;
	protected $guard = 'citizen';


	public function __construct(){
		$this->response = array(
			"msg" => "Bad Request.",
			"status" => FALSE,
			'status_code' => "BAD_REQUEST"
			);
		$this->response_code = 400;
		$this->transformer = new TransformerManager;
	}

	public function index(PageRequest  $request,$format = NULL){
		$guard = $request->segment(2);
		$per_page = $request->get('per_page',10);

		$citizen = $request->user($guard);

		$history = TravelHistory::where("citizen_id",$citizen->id)
							->orderBy('created_at',"DESC")
							->paginate($per_page);

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "TRAVEL_LIST";
		$this->response['msg'] = "Travel history list.";
		$this->response['data'] = $this->transformer->transform($history,new TravelHistoryTransformer,'collection');
		$this->response_code = 200;
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

	public function daily(PageRequest  $request,$format = NULL){
		$per_page = $request->get('per_page',10);
		$date = Carbon::parse($request->get('date',Carbon::now()->format("Y-m-d")));
		$type = Str::lower($request->get('type'));
		
		$history = TravelHistory::whereRaw("DATE(created_at) = '{$date->format('Y-m-d')}'")
							->where(function($query) use($type){
								if(strlen($type) > 0){
									return $query->where('type',$type);
								}
							})
							->orderBy('created_at',"DESC")
							->paginate($per_page);

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "TRAVEL_LIST";
		$this->response['msg'] = "Travel history list. Date - {$date->format('F d, Y')}, Type - {$type}";
		$this->response['data'] = $this->transformer->transform($history,new TravelHistoryTransformer,'collection');
		$this->response_code = 200;
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

	public function store(TravelHistoryRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$officer = $request->user('officer');
			$registered_citizen = FALSE;
			if($request->has('citizen_code')){
				$citizen = Citizen::where('code',Str::upper($request->get('citizen_code')))->where('status','active')->first();

				if(!$citizen){
					$this->response['status'] = FALSE;
					$this->response['status_code'] = "INVALID_CITIZEN";
					$this->response['msg'] = "Citizen record invalid.";
					$this->response_code = 424;
					goto callback;
				}
				$registered_citizen = TRUE;

			}
			
			$travel = new TravelHistory;
			$travel->officer_id = $officer->id;
			if($registered_citizen){
				$travel->citizen_id = $citizen->id;
				$travel->fname = $citizen->fname;
				$travel->lname = $citizen->lname;
				$travel->contact_number = $citizen->contact_number;

				if($citizen->birthdate){
					$travel->age = $citizen->birthdate? $citizen->birthdate->diffInYears(Carbon::now()): NULL;
				}
				$travel->residence_address = $citizen->residence_address;
				$travel->brgy = $citizen->brgy?:"";
				$travel->district = $citizen->district?:"";
			}else{
				$travel->fname = Str::upper($request->get('fname'));
				$travel->lname = Str::upper($request->get('lname'));
				$travel->age = Str::upper($request->get('age'));
				$travel->residence_address = Str::upper($request->get('residence_address'));
				$travel->brgy = Str::upper($request->get('brgy'));

				$brgy = Barangay::whereRaw("UPPER(name) = '{$travel->brgy}'" )->first();
				$travel->district = "";
				if($brgy) $travel->district = $brgy->district;
				$travel->contact_number = $request->get('contact_number');

			}

			$travel->geo_lat = $request->get('geo_lat');
			$travel->geo_long = $request->get('geo_long');
			$travel->type = Str::lower($request->get('type')) == "entry" ? "entry" : "exit";
			$travel->status = Str::lower($request->get('status')) == "approved" ? "approved" :"declined";


			$reasons = ["reason_food_medicine","reason_hospital","reason_bank"];

			foreach($reasons as $index => $field){
				$travel->{"{$field}"} = "no";
				if($request->has($field) AND $request->get($field) == "yes"){
					$travel->{"{$field}"} = Str::lower($request->get($field));
				}
			}

			if($request->has('reason_other') AND $request->has('reason_other') == "yes"){
				$travel->reason_other = $request->get('reason_other_description');
			}
			$travel->save();
			$date_today = Carbon::now()->format("Y-m-d");
			$total = TravelHistory::where('id','<=',$travel->id)
									->where('type',$travel->type)
									->whereRaw("DATE(created_at) = '{$date_today}'")
									->count();
			$prefix = $travel->type == "entry" ? "ENT" : "EXT";
			$travel->code = $prefix."-".Carbon::now()->format("ymd").str_pad($total, 4, "0", STR_PAD_LEFT);
			$travel->save();

			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "HISTORY_ADDED";
			$this->response['msg'] = "New history has been added.";
			$this->response['data'] = $this->transformer->transform($travel,new TravelHistoryTransformer,'item');
			$this->response_code = 201;


		}catch(\Exception $e){
			DB::rollback();
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "SERVER_ERROR";
			$this->response['msg'] = "Server Error: Code #{$e->getLine()}";
			$this->response_code = 500;
		}
		
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

	public function show(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);
		$travel = $request->get('travel_data');
		if($guard == "citizen"){
			$citizen = $request->user($guard);

			if($citizen->id != $travel->citizen_id){
				$this->response['status'] = FALSE;
				$this->response['status_code'] = "RESTRICTED_DATA";
				$this->response['msg'] = "No permission assigned to this account to view the record.";
				$this->response_code = 401;
				goto callback;
			}
		}

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "TRAVEL_INFORMATION";
		$this->response['msg'] = "Travel history information.";
		$this->response['data'] = $this->transformer->transform($travel,new TravelHistoryTransformer,'item');
		$this->response_code = 200;
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

}