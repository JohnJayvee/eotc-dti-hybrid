<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Api\ProfileRequest;
use App\Laravel\Requests\Api\PasswordRequest;
use App\Laravel\Requests\Api\ProfileImageRequest;


/* Models
 */
use App\Laravel\Models\{Citizen,User};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,CitizenTransformer,OfficerTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class ProfileController extends Controller{
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

	public function identification_card(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);
		$user = $request->user($guard);

		$identification = $user->identification;

		if(!$identification){
			$this->response['status'] = FALSE;
			$this->response['status_code'] = "NO_APPLICATION_FOUND";
			$this->response['msg'] = "No ID Application found.";
			$this->response_code = 412;
			goto callback;
		}

		switch($identification->status){
			case 'pending':
				$this->response['status'] = FALSE;
				$this->response['status_code'] = "PENDING_APPLICATION";
				$this->response['msg'] = "No ID Application found.";
				$this->response_code = 412;
				goto callback;
			break;

			case 'declined':
				$this->response['status'] = FALSE;
				$this->response['status_code'] = "NO_APPLICATION_FOUND";
				$this->response['msg'] = "No ID Application found.";
				$this->response_code = 412;
				goto callback;
			break;
		}

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "PROFILE_INFO";
		$this->response['msg'] = "Profile information.";
		$this->response['url'] = route('system.print.id',[$identification->id]);
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
	
	public function travel_pass(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);
		$user = $request->user($guard);

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "TRAVEL_PASS";
		$this->response['msg'] = "Travel Pass information.";
		$this->response['url'] = route('system.print.travel_pass',[$user->id]);
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

	public function show(PageRequest $request,$format = NULL){
		$guard = $request->segment(2);
		$user = $request->user($guard);
		$this->response['status'] = TRUE;
		$this->response['status_code'] = "PROFILE_INFO";
		$this->response['msg'] = "Profile information.";
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

	public function update_profile(ProfileRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$guard = $request->segment(2);

			$user = $request->user($guard);
			$user->fname = Str::upper($request->get('fname'));
			$user->mname = Str::upper($request->get('mname'));
			$user->lname = Str::upper($request->get('lname'));
			$user->suffix = Str::upper($request->get('suffix'));
			if($guard == "officer"){
				$user->title = Str::upper($request->get('title'));
				$user->position = Str::upper($request->get('position'));	
			}

			// $user->residence_address = Str::upper($request->get('residence_address'));
			// $user->username = Str::lower($request->get('username'));
			$user->email = Str::lower($request->get('email'));
			$user->contact_number = $request->get('contact_number');

			// $special_fields = ['civil_status'];
			// $date_fields = ['birthdate'];

			// foreach($date_fields as $index => $field){
			// 	$user->{"{$field}"} = NULL;
			// 	if($request->has($field) AND !is_null($request->get($field))){
			// 		$user->{"{$field}"} = Helper::date_db($request->get($field));
			// 	}
			// }

			// foreach($special_fields as $index => $field){
			// 	$user->{"{$field}"} = NULL;
			// 	if($request->has($field)){
			// 		$user->{"{$field}"} = Str::upper($request->get($field));
			// 	}
			// }

			$user->save();
			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "PROFILE_UPDATED";
			$this->response['msg'] = "Profile updated  successfully.";

			if($guard == "citizen"){
			$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
			}

			if($guard == "officer"){
			$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
			}
			$this->response_code = 200;


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

	public function update_avatar(ProfileImageRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$guard = $request->segment(2);
			$user = $request->user($guard);
			
			$image = ImageUploader::upload($request->file('file'), "uploads/avatar");
			$user->path = $image['path'];
			$user->directory = $image['directory'];
			$user->filename = $image['filename'];
			$user->source = $image['source'];
			$user->save();
			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "PASSWORD_UPDATED";
			$this->response['msg'] = "New profile image has been set.";

			if($guard == "citizen"){
			$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
			}

			if($guard == "officer"){
			$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
			}

			$this->response_code = 200;


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

	public function update_password(PasswordRequest $request,$format = NULL){
		DB::beginTransaction();
		try{
			$guard = $request->segment(2);

			$user = $request->user($guard);
			$user->password = bcrypt($request->get('password'));
			$user->save();
			DB::commit();

			$this->response['status'] = TRUE;
			$this->response['status_code'] = "PASSWORD_UPDATED";
			$this->response['msg'] = "New password has been set.";

			if($guard == "citizen"){
			$this->response['data'] = $this->transformer->transform($user,new CitizenTransformer,'item');
			}

			if($guard == "officer"){
			$this->response['data'] = $this->transformer->transform($user,new OfficerTransformer,'item');
			}

			$this->response_code = 200;


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