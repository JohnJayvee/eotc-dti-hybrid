<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;


use App\Laravel\Models\{Transaction,Department,Application};

/* App Classes
 */
use Carbon,Auth,DB,Str,Helper;

class ReportController extends Controller
{
    protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['departments'] = ['' => "Choose Department"] + Department::pluck('name', 'id')->toArray();

		$this->data['types'] = ['' => "Choose Type",'PENDING' => "New Submission" , 'APPROVED' => "Approved Applications",'DECLINED' => "Declined Applications",'resent' => "Resent Applications"];

		$this->data['status'] = ['' => "Choose Payment Status",'PAID' => "Paid" , 'UNPAID' => "Unpaid"];
		$this->data['payment_methods'] = ['' => "Choose Payment Method",'ONLINE' => "Online" , 'OTC' => "Over the Counter"];

		
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Reports";
		$auth = Auth::user();
		if ($auth->type == "super_user" || $auth->type == "admin") {

			$this->data['applications'] = ['' => "Choose Applications"] + Application::pluck('name', 'id')->toArray();

			$first_record = Transaction::orderBy('created_at','ASC')->first();
			$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

			if($first_record){
				$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
			}
			$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
			$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

			$this->data['selected_type'] = $request->get('type');
			$this->data['selected_department_id'] = $request->get('department_id');
			$this->data['selected_application_id'] = $request->get('application_id');
			$this->data['selected_payment_method'] = $request->get('payment_method');
			$this->data['selected_payment_status'] = $request->get('payment_status');
			$this->data['keyword'] = Str::lower($request->get('keyword'));

			$this->data['resent'] = NULL;
			if ($request->get('type') == "resent") {
				$this->data['resent'] = "1";
			}

			$this->data['transactions'] = Transaction::where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_type']) > 0){
						return $query->where('status',$this->data['selected_type']);
					}
				})
				->where(function($query){
					if(strlen($this->data['resent']) > 0){
						return $query->where('is_resent',$this->data['resent']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_department_id']) > 0){
						return $query->where('department_id',$this->data['selected_department_id']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_application_id']) > 0){
						return $query->where('application_id',$this->data['selected_application_id']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_method']) > 0){
						return $query->where('payment_method',$this->data['selected_payment_method'])
								->orWhere('application_payment_method',$this->data['selected_payment_method']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_status']) > 0){
						return $query->where('payment_status',$this->data['selected_payment_status'])
								->orWhere('application_payment_status',$this->data['selected_payment_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page);

			return view('system.report.index',$this->data);
		}elseif ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();

			$first_record = Transaction::orderBy('created_at','ASC')->first();
			$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

			if($first_record){
				$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
			}
			$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
			$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

			$this->data['selected_application_id'] = $request->get('application_id');
			$this->data['selected_payment_status'] = $request->get('payment_status');
			$this->data['selected_payment_method'] = $request->get('payment_method');
			$this->data['keyword'] = Str::lower($request->get('keyword'));

			$this->data['transactions'] = Transaction::where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_application_id']) > 0){
						return $query->where('application_id',$this->data['selected_application_id']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_method']) > 0){
						return $query->where('payment_method',$this->data['selected_payment_method']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_status']) > 0){
						return $query->where('payment_status',$this->data['selected_payment_status'])
								->orWhere('application_payment_status',$this->data['selected_payment_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page);
				
			return view('system.report.bureau-report',$this->data);
		}
		
	}

}
