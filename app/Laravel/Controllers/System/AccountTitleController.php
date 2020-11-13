<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\AccountTitleRequest;
/*
 * Models
 */
use App\Laravel\Models\{AccountTitle,Department};
/* App Classes
 */


use Carbon,Auth,DB,Str,Helper,Excel;

class AccountTitleController extends Controller
{
    protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		$this->data['department'] = ['' => "All Bureau/Office"] + Department::pluck('name','id')->toArray();
		array_merge($this->data, parent::get_data());
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Account Title";
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		$this->data['account_titles'] = AccountTitle::where(function($query){
		if(strlen($this->data['keyword']) > 0){
			return $query->WhereRaw("LOWER(name)  LIKE  '%{$this->data['keyword']}%'");
			}
		})->orderBy('created_at',"DESC")->paginate($this->per_page);
		return view('system.account-title.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= " - Add new record";
		return view('system.account-title.create',$this->data);
	}
	public function store(AccountTitleRequest $request){
		DB::beginTransaction();
		try{
			$new_account_title = new AccountTitle;
			$new_account_title->name = $request->get('name');
			$new_account_title->department_id = $request->get('department_id');
			
			$new_account_title->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Account Title has been added.");
			return redirect()->route('system.account_title.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return response()->json(['success'=>'errorList','message'=> $e->errors()]);

		}
	}

	public function  edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= " - Edit record";
		$this->data['account_title'] = $request->get('account_title_data');
		return view('system.account-title.edit',$this->data);
	}

	public function  update(AccountTitleRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$account_title = $request->get('account_title_data');
			$account_title->name = $request->get('name');
			$account_title->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Account Title had been modified.");
			return redirect()->route('system.account_title.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  destroy(PageRequest $request,$id = NULL){
		$account_title = $request->get('account_title_data');
		DB::beginTransaction();
		try{
			$account_title->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Account Title removed successfully.");
			return redirect()->route('system.account_title.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}



	
}
