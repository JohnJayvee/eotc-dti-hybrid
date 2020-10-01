<?php 

namespace App\Laravel\Controllers\Api;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\TransactionRequest;

/* Models
 */
// use App\Laravel\Models\{Barangay};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager, TransactionTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller{
	protected $response = [];
	protected $response_code;
    protected $guard = 'citizen';
    
    protected $data;

	public function __construct(){
		$this->response = array(
			"msg" => "aTotal is not equal to the details",
			"status" => FALSE,
			'status_code' => "Invalid_data"
            );
		$this->response_code = 400;
        $this->transformer = new TransformerManager;
        $this->client = new \GuzzleHttp\Client();
        $this->headers =  [
            'x-token'     => '$2a$12$Pl3qbFzblTbCLOjJipkwYuHuIZ5oqdZpafaBVyqOm43TwaGUVUh4S',
            'x-secret' => 'C4OD0MK757F5UOR89ZJE',
            'Content-Type' => 'application/json',
        ];
    }

	public function  store(TransactionRequest $request, $format = NULL){
        /*
            is "total" is going tobe computed here? or is it already an input value?
        */
            $postrequest = $this->client->post('http://staging.digipep.ziapay.ph/api/transaction/store', [
                'headers' =>  $this->headers,
                'json'  => $request->all()
                ]);
            $this->response= json_decode($postrequest->getBody(), true);
            
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

    public function  show(Request $request, $format = NULL){
            $data = request()->validate([
                'qrCode' => 'required'
            ]);
            try{
            $postrequest = $this->client->post('staging.digipep.ziapay.ph/api/transaction/inquire', [
                'headers' =>  $this->headers,
                'json'  => $data
                ]);
                
            $this->response= json_decode($postrequest->getBody(), true);
            $this->response_code = 200;
            }catch(Exception $e){
                return $e->getMessage();
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
}


// $body = [
//     'referenceCode' => $request->referenceCode,
//     "total" => $request->total,
//     "firstname"  => $request->firstname,
//     "lastname"  => $request->lastname,
//     "subMerchantCode"  => $request->subMerchantCode,
//     "subMerchantName"  => $request->subMerchantName,
//     "title"  => $request->title,
//     "emailAddress"  => $request->emailAddress,
//     "contactNumber"  => $request->contactNumber,
//     "returnUrl"  => $request->returnUrl,
//     "successUrl"  => $request->successUrl,
//     "cancelUrl"  => $request->cancelUrl,
//     "failedUrl"  => $request->failedUrl,
//     "details" => [
//         "particularFee" => $request->details["particularFee"],
//         "penaltyFee" => $request->details["penaltyFee"],
//         "dstFee" => $request->details["dstFee"],
//     ]
// ];