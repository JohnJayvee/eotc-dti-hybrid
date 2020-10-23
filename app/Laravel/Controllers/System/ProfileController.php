<?php 

namespace App\Laravel\Controllers\System;


/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\{ProfileRequest,ProfilePasswordRequest,ProfileImageRequest};

/*
 * Models
 */
use App\Laravel\Models\Application;
use App\Laravel\Models\Employee;
use App\Laravel\Models\EmployeeDocument;
use App\Laravel\Models\EmployeeLeaveCredit;

/* App Classes
 */
use Carbon,Auth,DB,Str,ImageUploader;

class ProfileController extends Controller{

	protected $data;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
	}

	public function index(PageRequest $request){
		$this->data['account'] = Auth::user();
		$this->data['application_list'] = Application::whereIn('id',explode(",", $this->data['account']->application_id))->get();

		/*$this->data['application_list'] = [];

		foreach ($application as $key => $value) {
			array_push($this->data['application_list'], $value->name);
		}
*/

		return view('system.profile.index',$this->data);
	}

	public function edit(PageRequest $request){
		$this->data['page_title'] .= " - Update Personal Information";
		$this->data['account'] = Auth::user();

		return view('system.profile.edit',$this->data);
	}

	public function update(ProfileRequest $request){
		DB::beginTransaction();
		try{
			$account = Auth::user();

			$account->email = Str::lower($request->get('email'));
			$account->contact_number = $request->get('contact_number');
			$account->fname = $request->get('fname');
			$account->mname = $request->get('mname');
			$account->lname = $request->get('lname');

			$account->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Account Information modified successfully.");
			return redirect()->route('system.profile.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();

		}
	}

	public function edit_password(PageRequest $request){
		$this->data['page_title'] .= " - Update Password";

		return view('system.profile.password',$this->data);
	}

	public function update_image(ProfileImageRequest $request){
		DB::beginTransaction();
		try{
			$auth = $request->user();
			$image = ImageUploader::upload($request->file('file'), "uploads/avatar");
			$auth->path = $image['path'];
			$auth->directory = $image['directory'];
			$auth->filename = $image['filename'];
			$auth->source = $image['source'];
			$auth->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Profile Picture successfully modified.");
			return redirect()->route('system.employee.show',[$auth->id]);
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getMessage()}");
			return redirect()->back();

		}
	}

	public function update_password(ProfilePasswordRequest $request){
		DB::beginTransaction();
		try{
			$employee = Auth::user();
			$employee->password = bcrypt($request->get('password'));
			$employee->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Password modified successfully.");
			return redirect()->route('system.profile.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();

		}
	}
}