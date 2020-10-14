<?php 

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;

/*
 * Models
 */
use App\Laravel\Models\{Transaction,TransactionRequirements,Department,RegionalOffice,ApplicationRequirements,Application};

use App\Laravel\Requests\System\ProcessorTransactionRequest;

use App\Laravel\Events\SendApprovedReference;
use App\Laravel\Events\SendDeclinedReference;

use App\Laravel\Events\SendProcessorTransaction;
use App\Laravel\Events\SendEmailProcessorTransaction;

use App\Laravel\Events\SendDeclinedEmailReference;
use App\Laravel\Events\SendApprovedEmailReference;
/* App Classes
 */
use Carbon,Auth,DB,Str,ImageUploader,Helper,Event,FileUploader;

class TransactionController extends Controller{

	protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());

		if (Auth::user()->type == "super_user" || Auth::user()->type == "admin") {
			$this->data['department'] = ['' => "Choose Department"] + Department::pluck('name', 'id')->toArray();
		}elseif (Auth::user()->type == "office_head" || Auth::user()->type == "processor") {
			$this->data['department'] = ['' => "Choose Department"] + Department::where('id',Auth::user()->department_id)->pluck('name', 'id')->toArray();
		}

		$this->data['regional_offices'] = ['' => "Choose Regional Offices"] + RegionalOffice::pluck('name', 'id')->toArray();
		$this->data['requirements'] =  ApplicationRequirements::pluck('name','id')->toArray();
		$this->data['status'] = ['' => "Choose Payment Status",'PAID' => "Paid" , 'UNPAID' => "Unpaid"];
		

		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Transactions";
		$this->data['transactions'] = Transaction::orderBy('created_at',"DESC")->get(); 
		return view('system.transaction.index',$this->data);
	}

	public function pending (PageRequest $request){
		$this->data['page_title'] = "Pending Transactions";

		$auth = Auth::user();
		$this->data['auth'] = Auth::user();

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');

		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
		$this->data['selected_application_ammount_status'] = $request->get('application_ammount_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		if ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}elseif ($auth->type == "processor") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
		}


		$this->data['transactions'] = Transaction::where('status',"PENDING")->where('is_resent',0)->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "office_head") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}
						
					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
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

		return view('system.transaction.pending',$this->data);
	}
	public function approved (PageRequest $request){
		$this->data['page_title'] = "Approved Transactions";
		$auth = Auth::user();
		$this->data['auth'] = Auth::user();

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');

		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_application_status'] = $request->get('application_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		if ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}elseif ($auth->type == "processor") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
		}

		$this->data['transactions'] = Transaction::where('status',"APPROVED")->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "office_head") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}
						
					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
					}
				})
				
				->where(function($query){
					if(strlen($this->data['selected_application_status']) > 0){
						return $query->where('application_payment_status',$this->data['selected_application_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page); 

		return view('system.transaction.approved',$this->data);

	}
	public function declined (PageRequest $request){
		$this->data['page_title'] = "Declined Transactions";
		$auth = Auth::user();
		$this->data['auth'] = Auth::user();

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');

		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		if ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}elseif ($auth->type == "processor") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
		}
		
		$this->data['transactions'] = Transaction::where('status',"DECLINED")
				->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "office_head") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}
						
					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
					}
				})
				
				->where(function($query){
					if(strlen($this->data['selected_processing_fee_status']) > 0){
						return $query->where('payment_status',$this->data['selected_processing_fee_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page); 
				
		return view('system.transaction.declined',$this->data);
	}
	public function resent (PageRequest $request){
		$this->data['page_title'] = "Resent Transactions";
		
		$auth = Auth::user();
		$this->data['auth'] = Auth::user();

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');

		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		if ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}elseif ($auth->type == "processor") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
		}

		$this->data['transactions'] = Transaction::where('is_resent',1)->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',mname,' ',lname))  LIKE  '{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "office_head") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}
						
					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_processing_fee_status']) > 0){
						return $query->where('payment_status',$this->data['selected_processing_fee_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page); 

		return view('system.transaction.resent',$this->data);
	}


	public function show(PageRequest $request,$id = NULL){
		$this->data['count_file'] = TransactionRequirements::where('transaction_id',$id)->count();
		$this->data['attachments'] = TransactionRequirements::where('transaction_id',$id)->get();
		$this->data['transaction'] = $request->get('transaction_data');
		$id = $this->data['transaction']->requirements_id;

		$this->data['physical_requirements'] = ApplicationRequirements::whereIn('id',explode(",", $id))->get();
		
		$this->data['page_title'] = "Transaction Details";
		return view('system.transaction.show',$this->data);
	}
	
	public function create(PageRequest $request){
		$this->data['page_title'] = "- Add New Record";
		$auth= Auth::user();
		if($auth->type == "processor"){
			$this->data['applications'] = ['' => "Choose Application Type"] + Application::whereIn('id',explode(",",$auth->application_id))->pluck('name','id')->toArray();

		}

		return view('system.transaction.create',$this->data);
	}

	public function store(ProcessorTransactionRequest $request){

		$full_name = $request->get('firstname') ." ". $request->get("middlename") ." ". $request->get('lastname');
		
		

			$new_transaction = new Transaction;
			$new_transaction->company_name = $request->get('company_name');
			$new_transaction->fname = $request->get('firstname');
			$new_transaction->mname = $request->get("middlename");
			$new_transaction->lname = $request->get('lastname');
			$new_transaction->email = $request->get('email');
			$new_transaction->contact_number = $request->get('contact_number');
			/*$new_transaction->regional_id = $request->get('regional_id');
			$new_transaction->regional_name = $request->get('regional_name');*/
			$new_transaction->processing_fee = Helper::db_amount($request->get('processing_fee'));
			$new_transaction->application_id = $request->get('application_id');
			$new_transaction->application_name = $request->get('application_name');
			$new_transaction->department_id = $request->get('department_id');
			$new_transaction->department_name = $request->get('department_name');
			$new_transaction->payment_status = $request->get('processing_fee') > 0 ? "UNPAID" : "PAID";
			$new_transaction->transaction_status = $request->get('processing_fee') > 0 ? "PENDING" : "COMPLETED";
			$new_transaction->processor_user_id = Auth::user()->id;
			$new_transaction->requirements_id = implode(",", $request->get('requirements_id'));
			$new_transaction->process_by = "processor";
			$new_transaction->status = "APPROVED";
			$new_transaction->modified_at = Carbon::now();
			$new_transaction->hereby_check = $request->get('hereby_check');
			$new_transaction->amount = $request->get('amount');

			$new_transaction->save();

			$new_transaction->code = 'EOTC-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($new_transaction->id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));

			$new_transaction->processing_fee_code = 'PF-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($new_transaction->id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));

			$new_transaction->transaction_code = 'APP-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($new_transaction->id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));

			$new_transaction->document_reference_code = 'EOTC-DOC-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($new_transaction->id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));

			$new_transaction->save();


			$insert[] = [
				'email' => $new_transaction->email,
            	'contact_number' => $new_transaction->contact_number,
                'ref_num' => $new_transaction->processing_fee_code,
                'amount' => $new_transaction->amount,
                'transaction_code' => $new_transaction->transaction_code,
                'processing_fee' => $new_transaction->processing_fee,
                'full_name' => $new_transaction->customer_name,
                'application_name' => $new_transaction->application_name,
                'department_name' => $new_transaction->department_name,
                'created_at' => Helper::date_only($new_transaction->created_at)
        	];	

			$notification_data = new SendProcessorTransaction($insert);
		    Event::dispatch('send-transaction-processor', $notification_data);

		    $notification_email_data = new SendEmailProcessorTransaction($insert);
		    Event::dispatch('send-transaction-processor-email', $notification_email_data);
			
			DB::commit();

			session()->flash('notification-status', "success");
			session()->flash('notification-msg','Application was successfully submitted.');
			return redirect()->route('system.transaction.approved');
		
		

	}
	public function process($id = NULL,PageRequest $request){
		$type = strtoupper($request->get('status_type'));
		DB::beginTransaction();
		try{
			$transaction = $request->get('transaction_data');
			$transaction->status = $type;
			$transaction->amount = $type == "APPROVED" ? $request->get('amount') : NULL;
			$transaction->remarks = $type == "DECLINED" ? $request->get('remarks') : NULL;
			$transaction->processor_user_id = Auth::user()->id;
			$transaction->modified_at = Carbon::now();
			$transaction->save();

			if ($type == "APPROVED") {
				$requirements = TransactionRequirements::where('transaction_id',$transaction->id)->where('status',"pending")->update(['status' => "APPROVED"]);
				$insert[] = [
	            	'contact_number' => $transaction->contact_number,
	            	'email' => $transaction->email,
	                'ref_num' => $transaction->transaction_code,
	                'amount' => $transaction->amount,
	                'full_name' => $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name,
	                'application_name' => $transaction->application_name,
	                'department_name' => $transaction->department_name,
	                'modified_at' => Helper::date_only($transaction->modified_at)
            	];	

				$notification_data = new SendApprovedReference($insert);
			    Event::dispatch('send-sms-approved', $notification_data);

			    $notification_data_email = new SendApprovedEmailReference($insert);
			    Event::dispatch('send-email-approved', $notification_data_email);
			}
			if ($type == "DECLINED") {
				$requirements = TransactionRequirements::where('transaction_id',$transaction->id)->where('status',"pending")->update(['status' => "DECLINED"]);
				$insert[] = [
	            	'contact_number' => $transaction->contact_number,
	                'ref_num' => $transaction->document_reference_code,
	                'email' => $transaction->email,
	                'remarks' => $transaction->remarks,
	                'full_name' => $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name,
	                'application_name' => $transaction->application_name,
	                'department_name' => $transaction->department_name,
	                'modified_at' => Helper::date_only($transaction->modified_at),
	                'link' => "http://54.251.82.120/show-pdf/".$transaction->id,
            	];	

				$notification_data = new SendDeclinedReference($insert);
			    Event::dispatch('send-sms-declined', $notification_data);

			    $notification_data_email = new SendDeclinedEmailReference($insert);
			    Event::dispatch('send-email-declined', $notification_data_email);
			}
			

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Transaction has been successfully Processed.");
			return redirect()->route('system.transaction.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function process_requirements($id = NULL,$status = NULL,PageRequest $request){
		DB::beginTransaction();
		
		try{
			$transaction = TransactionRequirements::find($id);
			$transaction->status = $request->get('status');
			$transaction->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Requirements has been successfully ".$request->get('status').".");
			return redirect()->route('system.transaction.show',[$transaction->transaction_id]);
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  destroy(PageRequest $request,$id = NULL){
		$transaction = $request->get('transaction_data');
		DB::beginTransaction();
		try{
			$transaction->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Transaction removed successfully.");
			return redirect()->route('system.barangay.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	
}