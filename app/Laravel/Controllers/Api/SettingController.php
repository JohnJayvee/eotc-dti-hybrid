<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;

/* Models
 */
use App\Laravel\Models\{Barangay};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,BarangayTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class SettingController extends Controller{
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