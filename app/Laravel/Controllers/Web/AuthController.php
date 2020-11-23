<?php 

namespace App\Laravel\Controllers\Web;

/*
 * Request Validator
 */

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Web\RegisterRequest;

/*
 * Models
 */
use App\Laravel\Models\Customer;

use App\Laravel\Listeners\SendCode;

use Carbon,Auth,DB,Str,ImageUploader,Event;

class AuthController extends Controller{

	protected $data;
	
	public function __construct(){
		parent::__construct();
		
	}

	public function login($redirect_uri = NULL){

		$this->data['page_title'] = " :: Login";
		return view('web.auth.login',$this->data);
	}
	public function authenticate(PageRequest $request){

		try{
			$this->data['page_title'] = " :: Login";
			$email = $request->get('email');
			$password = $request->get('password');
			
			if(Auth::guard('customer')->attempt(['email' => $email,'password' => $password])){

				$user = Auth::guard('customer')->user();
				session()->put('auth_id', Auth::guard('customer')->user()->id);
				session()->flash('notification-status','success');
				session()->flash('notification-msg',"Welcome to DTI Online Pay, {$user->full_name}!");
				
				return redirect()->route('web.transaction.create');
			}
			session()->flash('notification-status','error');
			session()->flash('notification-msg','Wrong username or password.');
			return redirect()->back();

		}catch(Exception $e){
			abort(500);
		}
	}

	public function register(){
		$this->data['page_title'] = " :: Create Account";
		return view('web.auth.registration',$this->data);
	}
	public function store(RegisterRequest $request){
		DB::beginTransaction();
		try{
			$new_customer = new Customer;
			$new_customer->fill($request->all());
			$new_customer->region = $request->get('region');
			$new_customer->region_name = $request->get('region_name');
			$new_customer->town = $request->get('town');
			$new_customer->town_name = $request->get('town_name');
			$new_customer->barangay = $request->get('brgy');
			$new_customer->barangay_name= $request->get('brgy_name');
			$new_customer->street_name = $request->get('street_name');
			$new_customer->unit_number = $request->get('unit_number');
			$new_customer->zipcode = $request->get('zipcode');
			$new_customer->birthdate = $request->get('birthdate');
			$new_customer->tin_no = $request->get('tin_no');
			$new_customer->sss_no = $request->get('sss_no');
			$new_customer->phic_no = $request->get('phic_no');
			$new_customer->password = bcrypt($request->get('password'));
			$new_customer->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg','Successfully registered.');
			return redirect()->route('web.login');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
			
	}
	public function verify(){
		$this->data['page_title'] = " :: Verify Account";
		return view('web.auth.verify',$this->data);

	}

	public function verified($id = NULL , PageRequest $request){
		
		$verified_user = User::where('id',$id)->where('code',$request->get('code'))->first();

		if ($verified_user) {
			User::where('id',$id)->update(['active' => "1"]);
			session()->flash('notification-status', "success");
			session()->flash('notification-msg','Your Account has been Successfully verified.');
			return redirect()->route('web.login');
		}
		else{
			Session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Verification Failed");
			return redirect()->back();
		}

	}
	public function destroy(){
		Auth::guard('customer')->logout();
		session()->forget('auth_id');
		session()->flash('notification-status','success');
		session()->flash('notification-msg','You are now signed off.');
		return redirect()->route('web.login');
	}


}	
