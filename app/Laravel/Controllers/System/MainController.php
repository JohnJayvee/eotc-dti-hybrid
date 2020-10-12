<?php 

namespace App\Laravel\Controllers\System;


/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;


/*
 * Models
 */
use App\Laravel\Models\{Transaction,Department,Application};

/* App Classes
 */
use Carbon,Auth,DB,Str;

class MainController extends Controller{

	protected $data;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
	}

	public function dashboard(PageRequest $request){
		$auth = $request->user();
		$this->data['page_title'] .= "Dashboard";

		if ($auth->type == "admin" || $auth->type == "super_user") {
			$this->data['applications'] = Transaction::orderBy('created_at',"DESC")->get(); 
			$this->data['pending'] = Transaction::where('status',"PENDING")->count();
			$this->data['approved'] = Transaction::where('status',"APPROVED")->count(); 
			$this->data['declined'] = Transaction::where('status',"DECLINED")->count(); 
			$this->data['application_today'] = Transaction::whereDate('created_at', Carbon::now())->count(); 
			$this->data['labels'] = Department::pluck('name')->toArray();
			$this->data['transaction_per_department'] = Department::withCount('assignTransaction')->pluck('assign_transaction_count')->toArray();

			$transaction_query = Transaction::groupBy('department_id')->select("department_id", DB::raw('SUM(processing_fee + amount) AS amount_sum'));

		 	$this->data['amount_per_application'] = Department::leftjoin(DB::raw("({$transaction_query->toSql()}) AS tq"), 'tq.department_id', '=', 'department.id')->mergeBindings($transaction_query->getQuery())
							->select('department.*', 'amount_sum')->get();

			$this->data['total_amount'] = Transaction::select(DB::raw('sum(processing_fee + amount) as total'))->first();

			$chart_data = [];
			$per_month_date = [];
	    	$per_month_application =[];

	    	$approved_year_start = Carbon::now()->startOfYear()->subMonth();
	    	$declined_year_start = Carbon::now()->startOfYear()->subMonth();
	    	
			foreach(range(1,12) as $index => $value){
				$approved = Transaction::whereRaw("MONTH(created_at) = '".$approved_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','APPROVED');
				$total_approved = $approved->count();

				$declined = Transaction::whereRaw("MONTH(created_at) = '".$declined_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','DECLINED');
				$total_declined = $declined->count();

				array_push($per_month_application, ["month"=>$approved_year_start->format("M"),"approved"=>$total_approved,"declined"=>$total_declined]);
			}

			$this->data['per_month_application'] = json_encode($per_month_application);
			$this->data['label_data'] = json_encode($this->data['labels']);
			$this->data['chart_data'] = json_encode($this->data['transaction_per_department']);

		}elseif ($auth->type == "office_head") {

			$this->data['applications'] = Transaction::where('department_id',$auth->department_id)->orderBy('created_at',"DESC")->get(); 
			$this->data['pending'] = Transaction::where('department_id',$auth->department_id)->where('status',"PENDING")->count();
			$this->data['approved'] = Transaction::where('department_id',$auth->department_id)->where('status',"APPROVED")->count(); 
			$this->data['declined'] = Transaction::where('department_id',$auth->department_id)->where('status',"DECLINED")->count(); 
			$this->data['application_today'] = Transaction::where('department_id',$auth->department_id)->whereDate('created_at', Carbon::now())->count(); 

			$this->data['labels'] = Application::where('department_id',$auth->department_id)->pluck('name')->toArray();

			$this->data['transaction_per_application'] = Application::where('department_id',$auth->department_id)->withCount('assignAppTransaction')->pluck('assign_app_transaction_count')->toArray();

			$transaction_query = Transaction::groupBy('application_id')->select("application_id", DB::raw('SUM(processing_fee + amount) AS amount_sum'));

		 	$this->data['amount_per_application'] = Application::where('department_id',$auth->department_id)->leftjoin(DB::raw("({$transaction_query->toSql()}) AS tq"), 'tq.application_id', '=', 'application.id')->mergeBindings($transaction_query->getQuery())
							->select('application.*', 'amount_sum')->get();

			$this->data['total_amount'] = Transaction::where('department_id' , $auth->department_id)->select(DB::raw('sum(processing_fee + amount) as total'))->first();
			
			$chart_data = [];
			$per_month_date = [];
	    	$per_month_application =[];

	    	$approved_year_start = Carbon::now()->startOfYear()->subMonth();
	    	$declined_year_start = Carbon::now()->startOfYear()->subMonth();
	    	
			foreach(range(1,12) as $index => $value){
				$approved = Transaction::where('department_id',$auth->department_id)->whereRaw("MONTH(created_at) = '".$approved_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','APPROVED');
				$total_approved = $approved->count();

				$declined = Transaction::where('department_id',$auth->department_id)->whereRaw("MONTH(created_at) = '".$declined_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','DECLINED');
				$total_declined = $declined->count();

				array_push($per_month_application, ["month"=>$approved_year_start->format("M"),"approved"=>$total_approved,"declined"=>$total_declined]);
			}

			$this->data['per_month_application'] = json_encode($per_month_application);

			$this->data['label_data'] = json_encode($this->data['labels']);
			$this->data['chart_data'] = json_encode($this->data['transaction_per_application']);

		}elseif ($auth->type == "processor") {

			$this->data['applications'] = Transaction::orderBy('created_at',"DESC")->get(); 
			$this->data['pending'] = Transaction::whereIn('application_id',explode(",", $auth->application_id))->where('status',"PENDING")->count();
			$this->data['approved'] = Transaction::whereIn('application_id',explode(",", $auth->application_id))->where('status',"APPROVED")->count(); 
			$this->data['declined'] = Transaction::whereIn('application_id',explode(",", $auth->application_id))->where('status',"DECLINED")->count(); 
			$this->data['application_today'] = Transaction::whereIn('application_id',explode(",", $auth->application_id))->whereDate('created_at', Carbon::now())->count(); 

			$this->data['labels'] = Application::where('department_id',$auth->department_id)->pluck('name')->toArray();

			$this->data['transaction_per_application'] = Application::whereIn('id',explode(",", $auth->application_id))->withCount('assignAppTransaction')->pluck('assign_app_transaction_count')->toArray();

			// $transaction_query = Transaction::groupBy('application_id')->select("application_id", DB::raw('SUM(processing_fee + amount) AS amount_sum'));

		 // 	$this->data['amount_per_application'] = Application::whereIn('id',explode(",", $auth->application_id))->leftjoin(DB::raw("({$transaction_query->toSql()}) AS tq"), 'tq.application_id', '=', 'application.id')->mergeBindings($transaction_query->getQuery())
			// 				->select('application.*', 'amount_sum')->get();

			// $this->data['total_amount'] = Transaction::whereIn('application_id' ,explode(",", $auth->application_id))->select(DB::raw('sum(processing_fee + amount) as total'))->first();

			$chart_data = [];
			$per_month_date = [];
	    	$per_month_application =[];

	    	$approved_year_start = Carbon::now()->startOfYear()->subMonth();
	    	$declined_year_start = Carbon::now()->startOfYear()->subMonth();
	    	
			foreach(range(1,12) as $index => $value){
				$approved = Transaction::whereIn('id',explode(",", $auth->application_id))->whereRaw("MONTH(created_at) = '".$approved_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','APPROVED');
				$total_approved = $approved->count();

				$declined = Transaction::whereIn('id',explode(",", $auth->application_id))->whereRaw("MONTH(created_at) = '".$declined_year_start->addMonth()->format('m')."' AND YEAR(created_at) = '".Carbon::now()->format('Y')."'")->where('status','DECLINED');
				$total_declined = $declined->count();

				array_push($per_month_application, ["month"=>$approved_year_start->format("M"),"approved"=>$total_approved,"declined"=>$total_declined]);
			}

			$this->data['per_month_application'] = json_encode($per_month_application);
			$this->data['label_data'] = json_encode($this->data['labels']);
			$this->data['chart_data'] = json_encode($this->data['transaction_per_application']);

		}

		

		return view('system.dashboard',$this->data);
	}


}