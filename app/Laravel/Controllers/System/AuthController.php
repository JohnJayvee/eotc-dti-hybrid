<?php 

namespace App\Laravel\Controllers\System;


/*
 * Models
 */
use App\Laravel\Models\City;
use App\Laravel\Models\User;


/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\AdminRequest;
use App\Laravel\Requests\System\ActivationRequest;
use App\Laravel\Requests\System\SetupPasswordRequest;



use Carbon,Auth,DB,Str,Socialize;

class AuthController extends Controller{

	protected $data;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
	}

	public function login(){
		$this->data['page_title'] .=  " :: Login";
		return view('system.auth.login',$this->data);
	}

	public function authenticate(PageRequest $request,$uri = NULL){
		$password = $request->get('password');
		$username = Str::lower($request->get('username'));
		$remember_me = $request->get('remember_me',0);
		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		if(Auth::attempt(['username' => $username,'password' => $password])){
			// dd(Auth::user());exit;
			$account = Auth::user();

			if(Str::lower($account->status) != "active"){
				Auth::logout();
				session()->flash('notification-status','info');
				session()->flash('notification-msg','Account is not Activated yet.');
				return redirect()->route('system.auth.login');
			}
			if(in_array($account->type,['user'])){
				Auth::logout();
				session()->flash('notification-status','info');
            	session()->flash('notification-msg',"You don't have enough access to the requested page.");
				return redirect()->route('system.auth.login');
			}

			session()->put('auth_id',$account->id);
			if($uri AND session()->has($uri)){
				session()->flash('notification-status','success');
				session()->flash('notification-msg',"Welcome {$account->full_name}!");
				return redirect( session()->get($uri) );
			}

			session()->flash('notification-status','success');
			session()->flash('notification-msg',"Welcome {$account->full_name}!");
			return redirect()->route('system.dashboard');
		}elseif (Auth::attempt(['reference_id' => $username,'password' => $password])) {
			$account = Auth::user();
			if(Str::lower($account->status) != "active"){
				Auth::logout();
				session()->flash('notification-status','info');
				session()->flash('notification-msg','Account is not Activated yet.');
				return redirect()->route('system.auth.login');
			}
			if(in_array($account->type,['user'])){
				Auth::logout();
				session()->flash('notification-status','info');
            	session()->flash('notification-msg',"You don't have enough access to the requested page.");
				return redirect()->route('system.auth.login');
			}
			session()->put('auth_id',$account->id);
			if($uri AND session()->has($uri)){
				session()->flash('notification-status','success');
				session()->flash('notification-msg',"Welcome {$account->name}!");
				return redirect( session()->get($uri) );
			}

			session()->flash('notification-status','success');
			session()->flash('notification-msg',"Welcome {$account->name}!");
			return redirect()->route('system.dashboard');
		}

		session()->flash('notification-status','failed');
		session()->flash('notification-msg','Invalid account credentials.');
		return redirect()->back();
	}


	public function logout(){
		Auth::logout();
		session()->forget('auth_id');
		session()->flash('notification-status', "success");
		session()->flash('notification-msg','You are now signed off.');
		return redirect()->route('system.auth.login');
	}


	public function activate(){
		$this->data['page_title'] .=  " :: activate";
		return view('system.auth.activate',$this->data);
	}
	public function activate_account(ActivationRequest $request){
		$otp = $request->get('otp');
		$reference_id = $request->get('reference_id');

		
		$has_account = User::where('reference_id',$reference_id)->where('otp',$otp)->first();
		if ($otp) {
			if ($has_account) {
				if ($has_account->status == "inactive") {
					return redirect()->route('system.auth.change_password');
				}else{
					session()->flash('notification-status','failed');
					session()->flash('notification-msg','Account was Already Activated.');
					return redirect()->back();
				}
			}

		}
		
		session()->flash('notification-status','failed');
		session()->flash('notification-msg','Invalid credentials.');
		return redirect()->back();

	}
	public function change(){
		$this->data['page_title'] .=  " :: setup password";
		return view('system.auth.changepassword',$this->data);
	}

	public function setup_password(SetupPasswordRequest $request){

		$reference_number = $request->get('reference_number');
		DB::beginTransaction();
		try{
			$account = User::where('reference_id',$reference_number)->first();
			if ($account) {
				$account->password = bcrypt($request->get('password'));
				$account->status = "active";
				$account->otp = NULL;
				$account->save();
				DB::commit();
				session()->flash('notification-status', "success");
				session()->flash('notification-msg', "Your Password was successfully updated.");
				return redirect()->route('system.auth.login');
			}

			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Invalid Credentials");
			return redirect()->back();

		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
		
		
		
	}
}