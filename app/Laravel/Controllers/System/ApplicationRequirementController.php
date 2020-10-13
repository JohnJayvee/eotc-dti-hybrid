<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\ApplicationRequirementsRequest;
/*
 * Models
 */
use App\Laravel\Models\ApplicationRequirements;
/* App Classes
 */

use App\Laravel\Models\Imports\ApplicationRequirementsImport;

use Carbon,Auth,DB,Str,Excel;

class ApplicationRequirementController extends Controller
{
    protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['status_type'] = ['' => "Choose Type",'yes' =>  "Yes",'no' => "No"];
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Application Requirements";
		$this->data['application_requirements'] = ApplicationRequirements::orderBy('created_at',"DESC")->get(); 
		return view('system.application-requirements.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= " - Add new record";
		return view('system.application-requirements.create',$this->data);
	}
	public function store(ApplicationRequirementsRequest $request){
		DB::beginTransaction();
		try{
			$new_application_requirements = new ApplicationRequirements;
			$new_application_requirements->name = $request->get('name');
			$new_application_requirements->is_required = $request->get('is_required');
			$new_application_requirements->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Application Requirement has been added.");
			return redirect()->route('system.application_requirements.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= " - Edit record";
		$this->data['application_requirements'] = $request->get('requirement_data');
		return view('system.application-requirements.edit',$this->data);
	}

	public function  update(ApplicationRequirementsRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$department = $request->get('requirement_data');
			$department->name = $request->get('name');
			$department->is_required = $request->get('is_required');
			$department->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Application Requirement had been modified.");
			return redirect()->route('system.application_requirements.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  destroy(PageRequest $request,$id = NULL){
		$department = $request->get('department_data');
		DB::beginTransaction();
		try{
			$department->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Department removed successfully.");
			return redirect()->route('system.application_requirements.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
	public function  upload(PageRequest $request){
		$this->data['page_title'] .= " - Bulk Upload Department";
		return view('system.application-requirements.upload',$this->data);
	}

	public function upload_department(PageRequest $request) 
	{	
		try {
		    Excel::import(new ApplicationRequirementsImport, request()->file('file'));

		    session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Importing data was successful. [Note: if your data does not reflect , The Application Requirement name is already exist]");
			return redirect()->route('system.application_requirements.index');
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
		     $failures = $e->failures();
		     
		     foreach ($failures as $failure) {
		         $failure->row(); // row that went wrong
		         $failure->attribute(); // either heading key (if using heading row concern) or column index
		         $failure->errors(); // Actual error messages from Laravel validator
		         $failure->values(); // The values of the row that has failed.
		     }
		    dd($failures);
		    session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Something went wrong.");
			return redirect()->route('system.application_requirements.index');
		}
	    
	}
}
