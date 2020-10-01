<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\CitizenIdentificationApplicationRequest;


/* Models
 */
use App\Laravel\Models\{Barangay,Citizen,CitizenIdentification};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,CitizenIdentificationTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class CitizenIdentificationController extends Controller{
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

	public function  apply(CitizenIdentificationApplicationRequest $request, $format = NULL){
		DB::beginTransaction();
		try{
	        $citizen = auth('citizen')->user();

			$application = new CitizenIdentification;
			$application->citizen_id = $citizen->id;
			$application->fname = Str::upper($request->get('fname'));
			$application->lname = Str::upper($request->get('lname'));
			$application->mname = Str::upper($request->get('mname'));
			$application->suffix = Str::upper($request->get('suffix'));
			$application->email = Str::lower($request->get('email'));
			$application->contact_number = $request->get('contact_number');

			$brgy_name = Str::upper($request->get('residence_brgy'));
			$brgy = Barangay::whereRaw("UPPER(name) = '{$brgy_name}'")->first();
			$application->residence_brgy = Str::upper($brgy->name);
			$application->residence_district = Str::lower($brgy->district);

			$application->source_of_funds = implode(",",$request->get('source_of_funds'));
			$special_fields = ['civil_status','residence_street','permanent_street','permanent_brgy','permanent_city','permanent_province','gross_income','sss_gsis','tin_id','source_of_funds_other'];

			$date_fields = ['birthdate'];
			foreach($date_fields as $index => $field){
				$application->{"{$field}"} = NULL;
				if($request->has($field) AND !is_null($request->get($field))){
					$application->{"{$field}"} = Helper::date_db($request->get($field));
				}
			}

			foreach($special_fields as $index => $field){
				$application->{"{$field}"} = NULL;
				if($request->has($field)){
					$application->{"{$field}"} = Str::upper($request->get($field));
				}
			}

			$image = ImageUploader::upload($request->file('file'), "uploads/avatar");
			$application->path = $image['path'];
			$application->directory = $image['directory'];
			$application->filename = $image['filename'];
			$application->source = $image['source'];
			$application->status = "pending";
			$application->save();
			$application->official_id = "ID-".(Carbon::now()->format("ymd")).str_pad($application->id, 4, "0", STR_PAD_LEFT);
			$application->save();
			

			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "APPLICATION_SUBMITTED";
			$this->response['msg'] = "Application has been submitted.";
			// $this->response['data'] = $this->transformer->transform($user,new CitizenIdentificationTransformer,'item');

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

	public function get_barangay(PageRequest $request,$format = NULL){
		$barangay = Barangay::where('status','active')->get();
		$this->response['status'] = TRUE;
		$this->response['status_code'] = "BARANGAY_LIST";
		$this->response['msg'] = "List of Cebu City Barangay.";
		$this->response['data'] = $this->transformer->transform($barangay,new BarangayTransformer,'collection');
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