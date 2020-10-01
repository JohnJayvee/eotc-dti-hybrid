<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\PreRegisterRequest;
use App\Laravel\Requests\Api\RegisterRequest;
use App\Laravel\Requests\Api\OfficerRegisterRequest;

use App\Laravel\Requests\Api\LoginRequest;

/* Models
 */
use App\Laravel\Models\{Citizen,User};
use App\Laravel\Models\{AccountCode};



/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,CitizenTransformer,OfficerTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL;

class AuthController extends Controller{
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

	public function check_login(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);

		$user = auth($guard)->user();

		if(!$user){
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "UNAUTHORIZED";
			$this->response['msg'] = "Invalid/Expired token. Do refresh token.";
			$this->response_code = 401;
			goto  callback;
		}

		$user->last_login_at =  Carbon::now();
		$user->save();

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "LOGIN_SUCCESS";
		$this->response['msg'] = "Welcome {$user->name}!";
		if($guard == "citizen"){
		$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
		}

		if($guard == "officer"){
		$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
		}


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

	public function logout(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);

		$user = auth($guard)->user();

		
		if(!$user){
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "UNAUTHORIZED";
			$this->response['msg'] = "Invalid/Expired token. Do refresh token.";
			$this->response_code = 401;
			goto  callback;
		}

		auth($guard)->logout(true);
		

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "LOGOUT_SUCCESS";
		$this->response['msg'] = "Session closed.";
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

	public function validate_code(PageRequest $request,$format = NULL){
		$account_code = $request->get('account_code_data');
		switch($account_code->status){
			case 'available':
				$this->response['status'] = TRUE;
				$this->response['status_code'] = "AVAILABLE";
				$this->response['msg'] = "Reference code available for registration";
				$this->response_code = 202;
				goto  callback;
			break;

			default:
				$this->response['status'] = FALSE;
				$this->response['status_code'] = "NOT_AVAILABLE";
				$status = Str::title($account_code->status);
				$this->response['msg'] = "Reference Code already '{$status}'.";
				$this->response_code = 412;
				goto  callback;
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


	public function authenticate(PageRequest $request,$format = NULL){
		$email =  Str::lower($request->get('email'));
		$password  = $request->get('password');

		$field = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		$guard = $request->segment(2);

		if(!$token = auth($guard)->attempt([$field => $email,'password' => $password])){
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "UNAUTHORIZED";
			$this->response['msg'] = "Invalid account credentials.";
			$this->response_code = 401;
			goto  callback;
		}
		$user =  auth($guard)->user();

		$user->last_login_at =  Carbon::now();
		$user->save();


		$this->response['status'] = TRUE;
		$this->response['status_code'] = "LOGIN_SUCCESS";
		$this->response['msg'] = "Welcome {$user->name}!";
		$this->response['token'] = $token;
		$this->response['token_type'] = "bearer";
		if($guard == "citizen"){
		$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
		}

		if($guard == "officer"){
		$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
		}

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

	public function refresh_token(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);
		$user = $request->user($guard);
		if(!$user){
			$this->response = [
			    'msg' => "Invalid account.",
			    'status' => FALSE,
			    'status_code' => "INVALID_AUTH_USER",
			];
			$this->response_code = 423;
			goto callback;
		}
		$new_token = auth($this->guard)->refresh();
		$this->response['status'] = TRUE;
		$this->response['status_code'] = "ACCESS_TOKEN_UPDATED";
		$this->response['msg'] = "New access token assigned.";
		$this->response['token'] = $new_token;
		$this->response['token_type'] = "bearer";

		if($guard == "citizen"){
		$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
		}

		if($guard == "officer"){
		$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
		}


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

	public function  pre_register(PreRegisterRequest $request,$format = NULL){
		$this->response['status'] = TRUE;
		$this->response['status_code'] = "VALID_FIELD";
		$this->response['msg'] = "Field is  valid";

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

	public function store(RegisterRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$citizen = new Citizen;
			$citizen->email = Str::lower($request->get('email'));
			$citizen->contact_number = $request->get('contact_number');
			$citizen->fname = Str::upper($request->get('fname'));
			$citizen->lname = Str::upper($request->get('lname'));
			$citizen->username = Str::lower(Str::uuid()->toString());
			$citizen->status = "active";
			$citizen->password= bcrypt($request->get('password'));

			$type = Str::lower($request->get('type')) == "resident" ? "resident" : "visitor";
			$citizen->type = $type;
			$citizen->save();

			$citizen->code = "CEB-".str_pad($citizen->id, 4, "0", STR_PAD_LEFT);
			$citizen->save();

			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "REGISTERED";
			$this->response['msg'] = "Successfully registered.";
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

	public function store_officer(OfficerRegisterRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$account_code = $request->get('account_code_data');
			$officer = new User;
			$officer->email = Str::lower($request->get('email'));
			$officer->contact_number = $request->get('contact_number');
			$officer->fname = Str::upper($request->get('fname'));
			$officer->lname = Str::upper($request->get('lname'));
			$officer->mname = Str::upper($request->get('mname'));
			$officer->suffix = Str::upper($request->get('suffix'));

			$officer->username = Str::lower(Str::uuid()->toString());
			$officer->status = "active";
			$officer->password= bcrypt($request->get('password'));
			$officer->type = "officer";
			$officer->save();

			// $citizen->code = "OFFICER-".str_pad($citizen->id, 4, "0", STR_PAD_LEFT);
			$account_code->redeem_at = Carbon::now();
			$account_code->user_id = $officer->id;
			$account_code->status = "assigned";
			$account_code->save();
			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "REGISTERED";
			$this->response['msg'] = "Successfully registered.";
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
}