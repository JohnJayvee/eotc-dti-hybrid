<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\DepartmentRequest;
/*
 * Models
 */
use App\Laravel\Models\Department;
/* App Classes
 */
use Carbon,Auth,DB,Str;

class DepartmentController extends Controller
{
    protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Department";
		$this->data['departments'] = Department::orderBy('created_at',"DESC")->get(); 
		return view('system.department.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= " - Add new record";
		return view('system.department.create',$this->data);
	}
	public function store(DepartmentRequest $request){
		DB::beginTransaction();
		try{
			$new_department = new Department;
			$new_department->name = $request->get('name');
			
			$new_department->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Department has been added.");
			return redirect()->route('system.department.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();

		}
	}

	public function  edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= " - Edit record";
		$this->data['department'] = $request->get('department_data');
		return view('system.department.edit',$this->data);
	}

	public function  update(DepartmentRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$department = $request->get('department_data');
			$department->name = $request->get('name');
			$department->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Department had been modified.");
			return redirect()->route('system.department.index');
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
			return redirect()->route('system.department.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
}
