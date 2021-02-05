<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\ProcessorRequest;
use App\Laravel\Requests\System\ProcessorPasswordRequest;
/*
 * Models
 */
use App\Laravel\Models\User;
use App\Laravel\Models\Transaction;
use App\Laravel\Models\Department;
use App\Laravel\Models\Application;
/* App Classes
 */

use App\Laravel\Events\SendProcessorReference;
use App\Laravel\Events\SendEmailProcessorReference;
use Carbon,Auth,DB,Str,Hash,ImageUploader,Event;

class ProcessorController extends Controller
{
    protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['department'] = ['' => "Choose Bureau/Office"] + Department::pluck('name', 'id')->toArray();
		if(Auth::user()){
			if (Auth::user()->type == "admin" || Auth::user()->type == "office_head") {
				$this->data['user_type'] = ['' => "Choose Type",'office_head' => "Bureau/Office Head",'processor' => "Processor"];
			}else {
				$this->data['user_type'] = ['' => "Choose Type",'admin' => "Admin",'order_transaction_admin' => "Order Transaction Admin",'pcims_admin' => "PCIMS Admin",'bps_library_admin' => "BPS Library Admin",'bps_testing_admin' => "BPS Testing Admin",'office_head' => "Bureau/Office Head",'processor' => "Processor"];
			}

			if (Auth::user()->type == "super_user" || Auth::user()->type == "admin") {
				$this->data['department'] = ['' => "Choose Bureau/Office"] + Department::pluck('name', 'id')->toArray();
			}elseif (Auth::user()->type == "office_head" || Auth::user()->type == "processor") {
				$this->data['department'] = ['' => "Choose Bureau/Office"] + Department::where('id',Auth::user()->department_id)->pluck('name', 'id')->toArray();
			}
		}

		$this->data['status'] = ['' => "Choose Payment Status",'PAID' => "Paid" , 'UNPAID' => "Unpaid"];
		

		$this->data['status_type'] = ['' => "Choose Status",'active' =>  "Active",'inactive' => "Inactive"];
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Accounts";
		$auth = Auth::user();

		$this->data['keyword'] = Str::lower($request->get('keyword'));
		$this->data['selected_department_id'] = $auth->type == "office_head" ? $auth->department_id : $request->get('department_id');
		$this->data['selected_type'] = $request->get('type');

		$this->data['processors'] = User::where('type','<>','super_user')->where(function($query){
		if(strlen($this->data['keyword']) > 0){
			return $query->WhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
					->orWhereRaw("LOWER(reference_id) LIKE  '%{$this->data['keyword']}%'");
			}
		})
		->where(function($query){
			if ($this->data['auth']->type == "office_head") {
				return $query->where('department_id',$this->data['auth']->department_id);
			}else{
				if(strlen($this->data['selected_department_id']) > 0){
					return $query->where('department_id',$this->data['selected_department_id']);
				}
			}
		})
		->where(function($query){
			if(strlen($this->data['selected_type']) > 0){
				return $query->where('type',$this->data['selected_type']);
			}
		})
		->orderBy('created_at',"DESC")->paginate($this->per_page);
			
		
		return view('system.processor.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= "Processor - Add new record";
		$auth = Auth::user();

		if ($auth->type == "office_head") {
			$this->data['applications'] = Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}else{
			if(old('application_id') != NULL){
		    	$this->data['applications'] = Application::where('department_id',old('department_id'))->pluck('name', 'id')->toArray();
			}else{
				$this->data['applications'] = Application::pluck('name', 'id')->toArray();
			}
		}
		
		$ref_num = User::where('type','<>','super_user')->withTrashed()->latest('id')->first();
		$num =  $ref_num ? $ref_num->id : 1 ;

		$this->data['reference_number'] = str_pad($num + 1, 5, "0", STR_PAD_LEFT);
		
		if ($auth->type == "office_head") {
			return view('system.processor.processor-create',$this->data);
		}
		return view('system.processor.create',$this->data);
	}

	public function store(ProcessorRequest $request){
		$auth = Auth::user();
		DB::beginTransaction();
		try{
			$unique = uniqid();
			$new_processor = new User;
			$new_processor->fname = $request->get('fname');
			$new_processor->lname = $request->get('lname');
			$new_processor->mname = $request->get('mname');
			$new_processor->email = $request->get('email');
			$new_processor->type = strtolower($request->get('type'));
			$new_processor->department_id = $auth->type == "office_head" ? $auth->department_id : $request->get('department_id');	
			$new_processor->application_id = $request->get('application_id') ? implode(",", $request->get('application_id')) : NULL;
			$new_processor->reference_id = $request->get('reference_number');
			$new_processor->username = $request->get('username');
			$new_processor->contact_number = $request->get('contact_number');
			$new_processor->otp = substr($unique, 0, 10);
			if($request->hasFile('file')) { 
				$ext = $request->file->getClientOriginalExtension();
				if($ext == 'jpeg' || $ext == 'png' || $ext == 'jpg' || $ext == 'PNG' || $ext == 'JPEG' || $ext == 'JPG'){ 
					
					$upload_image = ImageUploader::upload($request->file, "uploads/image/users/profile");
					$new_processor->path = $upload_image['path'];
					$new_processor->directory = $upload_image['directory'];
					$new_processor->filename = $upload_image['filename'];
				}else{
					DB::rollback();
					session()->flash('notification-status', "failed");
					session()->flash('notification-msg', "Image Format is not supported");
					return redirect()->back();
				}
				
			}
			if ($new_processor->save()) {
				$insert[] = [
					'full_name' => $new_processor->fname ." " .$new_processor->lname,
					'ref_id' => $new_processor->reference_id,
	                'contact_number' => $new_processor->contact_number,
	                'otp' => $new_processor->otp,
	                'type' => $new_processor->type,
	                'email' => $new_processor->email
	            ];	
				/*$notification_data = new SendProcessorReference($insert);
			    Event::dispatch('send-sms-processor', $notification_data);*/

			    $notification_email_data = new SendEmailProcessorReference($insert);
		    	Event::dispatch('send-email-reference', $notification_email_data);

				DB::commit();
				session()->flash('notification-status', "success");
				session()->flash('notification-msg', "New ".str::title(str_replace("_", " ", $new_processor->type))." has been added.");
				return redirect()->route('system.processor.index');
			}
			
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();

		}
	}

	public function edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= "Processor - Edit record";

		$this->data['processor'] = $request->get('processor_data');

		if(old('application_id') != NULL || $this->data['processor']->department_id != NULL){
		
		    $this->data['applications'] = Application::where('department_id',old('department_id',$this->data['processor']->department_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = Application::pluck('name', 'id')->toArray();
		}

		return view('system.processor.edit',$this->data);
	}

	public function update(ProcessorRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$processor = $request->get('processor_data');
			$processor->fname = $request->get('fname');
			$processor->lname = $request->get('lname');
			$processor->mname = $request->get('mname');
			$processor->email = $request->get('email');
			$processor->type = $request->get('type');
			$processor->username = $request->get('username');
			$processor->contact_number = $request->get('contact_number');
			$processor->department_id = $request->get('department_id');
			$processor->application_id = $request->get('application_id') ? implode(",", $request->get('application_id')) : NULL;
			$processor->status = $request->get('status');
			if($request->hasFile('file')) { 
				$ext = $request->file->getClientOriginalExtension();
				if($ext == 'jpeg' || $ext == 'png' || $ext == 'jpg' || $ext == 'PNG' || $ext == 'JPEG' || $ext == 'JPG'){ 
					
					$upload_image = ImageUploader::upload($request->file, "uploads/image/users/profile");
					$processor->path = $upload_image['path'];
					$processor->directory = $upload_image['directory'];
					$processor->filename = $upload_image['filename'];
				}else{
					DB::rollback();
					session()->flash('notification-status', "failed");
					session()->flash('notification-msg', "Image Format is not supported");
					return redirect()->back();
				}
				
			}

			$processor->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', str::title($processor->type)." had been modified.");
			return redirect()->route('system.processor.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
	public function reset(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= "Processor - Reset Password";
		$this->data['processor'] = $request->get('processor_data');
		return view('system.processor.reset',$this->data);
	}
	public function update_password(ProcessorPasswordRequest $request,$id = NULL){
		
		DB::beginTransaction();
		try{
			$processor = $request->get('processor_data');
			if (Hash::check($request->get('current_password'), $processor->password)) {
				$processor->password = bcrypt($request->get('password'));
				$processor->save();
				DB::commit();
				session()->flash('notification-status', "success");
				session()->flash('notification-msg', "Password has been reset successfully.");
				return redirect()->route('system.processor.index');
			}else{
				DB::rollback();
				session()->flash('notification-status', "failed");
				session()->flash('notification-msg', "Invalid Current Password");
				return redirect()->back();
			}
		
			
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
	public function  destroy(PageRequest $request,$id = NULL){
		$processor = $request->get('processor_data');
		DB::beginTransaction();
		try{
			$processor->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Processor removed successfully.");
			return redirect()->route('system.processor.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  list(PageRequest $request){
		$this->data['page_title'] .= " List of Processor";
		$auth = Auth::user();

		$this->data['keyword'] = Str::lower($request->get('keyword'));
		$this->data['selected_department_id'] = $auth->type == "office_head" ? $auth->department_id : $request->get('department_id');

		$this->data['processors'] = User::where('type',"processor")->where(function($query){
		if(strlen($this->data['keyword']) > 0){
			return $query->WhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
					->orWhereRaw("LOWER(reference_id) LIKE  '%{$this->data['keyword']}%'");
			}
		})
		->where(function($query){
			if ($this->data['auth']->type == "office_head") {
				return $query->where('department_id',$this->data['auth']->department_id);
			}else{
				if(strlen($this->data['selected_department_id']) > 0){
					return $query->where('department_id',$this->data['selected_department_id']);
				}
			}
		})->orderBy('created_at',"DESC")->paginate($this->per_page);

		return view('system.processor.list',$this->data);
	}

	public function  show(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= " List of Transaction";
		$auth = Auth::user();
		
		$this->data['processor'] = User::find($id);

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");


		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
		$this->data['selected_application_ammount_status'] = $request->get('application_ammount_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));
		
		$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",",$this->data['processor']->application_id))->pluck('name', 'id')->toArray();
		

		$this->data['transactions'] = Transaction::where('processor_user_id',$id)->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(code) LIKE  '%{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_application_id']) > 0){
						return $query->where('application_id',$this->data['selected_application_id']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_processing_fee_status']) > 0){
						return $query->where('payment_status',$this->data['selected_processing_fee_status']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_application_ammount_status']) > 0){
						return $query->where('application_payment_status',$this->data['selected_application_ammount_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page);


		return view('system.processor.show',$this->data);
	}
}
