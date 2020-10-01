<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\ScannerQRRequest;
use App\Laravel\Requests\Api\ReportQRRequest;




/* Models
 */
use App\Laravel\Models\{Citizen,User,CitizenIdentification};
use App\Laravel\Models\{IATFIdentification,TravelHistory,Report};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,IATFIdentificationTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class ScannerController extends Controller{
	protected $response = [];
	protected $response_code;
	protected $guard = 'officer';


	public function __construct(){
		$this->response = array(
			"msg" => "Bad Request.",
			"status" => FALSE,
			'status_code' => "BAD_REQUEST"
			);
		$this->response_code = 400;
		$this->transformer = new TransformerManager;
	}

	public function submit_report(ReportQRRequest $request, $format = NULL){
		$officer = $request->user($this->guard);
		$id_no = Str::upper($request->get('iatf_id_no'));
		$current_location = $request->get('location');

		$report = new Report;
		$report->officer_id = $officer->id;
		$report->issued_id_no = Str::upper($id_no);
		$report->code = Str::upper(Carbon::now()->format("ym-")."RPT".Str::random(3));
		$report->geo_lat = $request->get('geo_lat');
		$report->geo_long = $request->get('geo_long');

		$report->location = $current_location;

		if($request->hasFile('file')){
			$file = FileUploader::upload($request->file('file'), "uploads/report");
			$report->path = $file['path'];
			$report->directory = $file['directory'];
			$report->filename = $file['filename'];
			$report->source = $file['source'];
		}
		$report->reason = $request->get('reason');
		$report->save();

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "REPORT_SUBMITTED";
		$this->response['msg'] = "Report submitted";
		$this->response_code = 201;

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

	public function scan_qr(ScannerQRRequest $request, $format = NULL){
		$officer = $request->user($this->guard);

		$id_no = Str::upper($request->get('id_no'));
		$iatf_identification = IATFIdentification::where('IATF_ISSUE_ID_NO',$id_no)->first();

		if(!$iatf_identification){
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "NOT_FOUND";
			$this->response['msg'] = "Identification card is invalid.";
			$this->response_code = 404;
			goto callback;
		}

		$current_location = $request->get('location');
		$travel_history = new TravelHistory;
		$travel_history->officer_id = $officer->id;
		$travel_history->issued_id_no = $iatf_identification->IATF_ISSUE_ID_NO;
		$travel_history->code = Str::upper(Carbon::now()->format("ym-").Str::random(6));
		$travel_history->fname = Str::upper($iatf_identification->FIRST_NAME);
		$travel_history->lname = Str::upper($iatf_identification->LAST_NAME);
		$travel_history->mname = Str::upper($iatf_identification->MIDDLE_NAME);
		$travel_history->suffix = Str::upper($iatf_identification->SUFFIX);
		$travel_history->designation = $iatf_identification->DESIGNATION;
		$travel_history->sector = $iatf_identification->SECTOR;
		$travel_history->company1 = Str::upper($iatf_identification->CO_NAME_CLEAN1) == "UNKNOWN" ? "-" :$iatf_identification->CO_NAME_CLEAN1;
		$travel_history->company2 = Str::upper($iatf_identification->CO_NAME_CLEAN1) == "UNKNOWN" ? "-" :$iatf_identification->CO_NAME_CLEAN2;

		$travel_history->geo_lat = $request->get('geo_lat');
		$travel_history->geo_long = $request->get('geo_long');
		$travel_history->location = $current_location;
		$travel_history->save();

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "ACCOUNT_INFO";
		$this->response['msg'] = "ID Information";
		$this->response_code = 200;
		$this->response['data'] = $this->transformer->transform($iatf_identification,new IATFIdentificationTransformer,'item');
		// $identification =  CitizenIdentification::where('official_id',Str::upper($request->get('citizen_code')))->first();

		// if($identification){
		// 	$citizen = Citizen::find($identification->citizen_id);
		// }else{
		// 	$citizen = Citizen::where('code',Str::upper($request->get('citizen_code')))->first();
		// }

		// if(!$citizen){

		// 	if($identification){
		// 		$citizen = $identification;
		// 		$type = "id_card";
		// 		goto jump_here;
		// 	}

		// 	$this->response['status'] = FALSE;
		// 	$this->response['status_code'] = "NOT_FOUND";
		// 	$this->response['msg'] = "No record found.";
		// 	$this->response_code = 404;
		// 	goto callback;
		// }

		// $type = "travel_pass";


		// if($citizen->status == "inactive"){
		// 	$this->response['status'] = FALSE;
		// 	$this->response['status_code'] = "INACTIVE_ACCOUNT";
		// 	$this->response['msg'] = "Account is already inactive.";
		// 	$this->response_code = 424;
		// 	goto callback;
		// }

		// if($citizen->status == "banned"){
		// 	$this->response['status'] = FALSE;
		// 	$this->response['status_code'] = "BANNED_ACCOUNT";
		// 	$this->response['msg'] = "Account banned.";
		// 	$this->response_code = 409;
		// 	goto callback;
		// }

		// jump_here:

		// $this->response['status'] = TRUE;
		// $this->response['status_code'] = "ACCOUNT_INFO";
		// $this->response['msg'] = "Account Information";
		// $this->response['type'] = $type;
		// if($type == "id_card"){
		// 	$this->response['data'] = $this->transformer->transform($citizen,new CitizenIdentificationTransformer,'item');
		// }else{
		// 	$this->response['data'] = $this->transformer->transform($citizen,new CitizenTransformer,'item');

		// }
		// $this->response_code = 200;

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