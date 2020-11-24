<?php 

namespace App\Laravel\Controllers\Web;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Models\Application;
use App\Laravel\Models\Transaction;
use App\Laravel\Models\ApplicationRequirements;
use App\Laravel\Models\AccountTitle;
use App\Laravel\Models\Exports\RCDExport;
/*
 * Models
 */

/* App Classes
 */
use Helper, Carbon, Session, Str,Auth,Input,DB,Excel;

class MainController extends Controller{

	protected $data;
	public function __construct () {
	}


	public function index(PageRequest $request){
		$this->data['page_title'] = "Homepage";
		return view('web.homepage',$this->data);
	}

	public function contact(PageRequest $request){
		$this->data['page_title'] = "Contact Us";
		return view('web.page.contact',$this->data);
	}
	public function application(PageRequest $request){
		$this->data['page_title'] = "Application";

		return view('web.page.application',$this->data);
	}


	public function get_application_type(PageRequest $request){
		$id = $request->get('department_id');
		$application = Application::where('account_title_id',$id)->get()->pluck('name', 'id');
		$response['msg'] = "List of Application";
		$response['status_code'] = "TYPE_LIST";
		$response['data'] = $application;
		callback:

		return response()->json($response, 200);
	}

	public function get_application(PageRequest $request){
		$id = $request->get('department_id');
		$application = Application::where('department_id',$id)->get()->pluck('name', 'id');
		$response['msg'] = "List of Application";
		$response['status_code'] = "TYPE_LIST";
		$response['data'] = $application;
		callback:

		return response()->json($response, 200);
	}

	public function get_account_title(PageRequest $request){
		$id = $request->get('department_id');
		$application = AccountTitle::where('department_id',$id)->get()->pluck('name', 'id');
		$response['msg'] = "List of Account Title";
		$response['status_code'] = "TYPE_LIST";
		$response['data'] = $application;
		callback:


		return response()->json($response, 200);
	}

	public function get_payment_fee(PageRequest $request){
		$id = $request->get('type_id');
		$payment_amount = Application::find($id);
		$response['msg'] = "List of Application";
		$response['status_code'] = "TYPE_LIST";
		$response['data'] = [$payment_amount->processing_fee,$payment_amount->partial_amount];
		callback:
		
		return response()->json($response, 200);
	}

	public function get_requirements(PageRequest $request){
		$id = $request->get('type_id');
		$application = Application::find($id);
		$required = [];
		$requirements = ApplicationRequirements::whereIn('id',explode(",", $application->requirements_id))->get();

		foreach ($requirements as $key => $value) {
			if ($value->is_required == "yes") {
				$string = $value->name . " (Required)";
				$string_id = "file".$value->id;
				$is_required = "required";
			}else{
				$string = $value->name . " (Optional)";
				$string_id = "file".$value->id;
				$is_required = " ";
			}
			
			array_push($required, [$string,$string_id,$value->id,$is_required]);
		}
		$response['msg'] = "List of Requirements";
		$response['status_code'] = "TYPE_LIST";
		$response['data'] = $required;
		callback:
		return response()->json($response, 200);
	}

	public function confirmation($code = NULL){
		sleep(10);
		$this->data['page_title'] = " :: confirmation";

		$prefix = explode('-', $code);
		$code = strtolower($code);

		switch (strtoupper($prefix[0])) {
			case 'APP':
				$transaction = Transaction::whereRaw("LOWER(transaction_code)  LIKE  '%{$code}%'")->first();
				$current_transaction_code = Str::lower($transaction->transaction_code);
				break;
			
			default:
				$transaction = Transaction::whereRaw("LOWER(processing_fee_code)  LIKE  '%{$code}%'")->first();
				$current_transaction_code = Str::lower($transaction->processing_fee_code);
				break;
		}

		$current_transaction_code = Str::lower(session()->get('transaction.code'));

		if($current_transaction_code == $code){
			
			session()->forget('transaction');
			$this->data['transaction'] = $transaction;
			$this->data['prefix'] = strtoupper($prefix[0]);
			return view('web._components.message',$this->data);
		}

		session()->flash('notification-status',"warning");
		session()->flash('notification-msg',"Transaction already completed. No more action is needed.");
		return redirect()->route('web.main.index');

	}
	public function rcd(PageRequest $request){
		
			 	$first_record = Transaction::orderBy('created_at','ASC')->first();
				$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

				/*if($first_record){
					$start_date = $response->start_date;
				}*/
				$this->data['start_date'] = Carbon::parse("11/10/2020")->format("Y-m-d");
				$this->data['end_date'] =  Carbon::parse("11/25/2020")->format("Y-m-d");


		        $transactions = Transaction::where('transaction_status', "COMPLETED")->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])->orderBy('created_at',"ASC")->get();

		        /*$sub_total = Transaction::select("created_at","collection_type", DB::raw('SUM(processing_fee) AS amount_sum'))->groupBy(DB::raw("DATE(created_at)"),"collection_type")->get();*/
		        return Excel::download(new RCDExport($transactions), 'RCD-record'.Carbon::now()->format('Y-m-d').'.xlsx');
			
  		
	 	 
    }
}