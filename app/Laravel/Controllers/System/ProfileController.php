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
		$this->data['employee'] = Auth::user();

		return view('system.profile.index',$this->data);
	}

	public function edit(PageRequest $request){
		$this->data['page_title'] .= " - Update Personal Information";
		$this->data['employee'] = Auth::user();

		return view('system.profile.edit',$this->data);
	}

	public function update(ProfileRequest $request){
		DB::beginTransaction();
		try{
			$employee = Auth::user();

			$employee->personal_email = Str::lower($request->get('personal_email'));
			$employee->contact_number = $request->get('contact_number');
			$employee->residence_address = Str::upper($request->get('residence_address'));

			$employee->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Personal Information modified successfully.");
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