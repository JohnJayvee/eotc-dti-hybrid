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
use Carbon,Auth,DB,Str;

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
			session()->flash('notification-msg', "Department had been modified.");
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
}
