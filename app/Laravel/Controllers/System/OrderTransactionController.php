<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\ExcelUploadRequest;


/*
 * Models
 */
use App\Laravel\Models\{OrderTransaction,OrderDetails};
use App\Laravel\Models\Imports\OrderImport;


/* App Classes
 */
use Carbon,Auth,DB,Str,Helper,Event,Excel;

class OrderTransactionController extends Controller
{
    protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());

		$this->data['status'] = ['' => "Choose Payment Status",'PAID' => "Paid" , 'UNPAID' => "Unpaid"];
		$this->data['method'] = ['' => "Choose Mode of Payment",'ONLINE' => "Online" , 'CASHIER' => "DTI Cashier"];
		$this->data['department'] = ['' => "Choose Type",'pcims_admin' => "PCIMS",'bps_library_admin' => "BPS Library",'bps_testing_admin' => "BPS Testing"];

		$this->per_page = env("DEFAULT_PER_PAGE",2);
	}

	

	public function pending (PageRequest $request){
		$this->data['page_title'] = "For Payment Transaction List";

		$auth = Auth::user();
		$this->data['auth'] = Auth::user();

		$first_record = OrderTransaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['keyword'] = Str::lower($request->get('keyword'));
		$this->data['selected_payment_status'] = $request->get('payment_status');
		$this->data['selected_payment_method'] = $request->get('payment_method');
		$this->data['selected_department_type'] = $request->get('department_type');

		$this->data['order_transactions'] = OrderTransaction::with('order')->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(payor)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(transaction_code) LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(order_transaction_number) LIKE  '%{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if (!in_array($this->data['auth']->type, ["order_transaction_admin","cashier"])) {
						return $query->where('department',$this->data['auth']->type);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_status']) > 0){
						return $query->where('payment_status',$this->data['selected_payment_status']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_method']) > 0){
						return $query->where('payment_method',$this->data['selected_payment_method']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_department_type']) > 0){
						return $query->where('department',$this->data['selected_department_type']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page);

		return view('system.order-transaction.pending',$this->data);
	}

	public function  upload(PageRequest $request){
		$this->data['page_title'] .= " - Bulk Upload Orders";
		return view('system.order-transaction.upload-order',$this->data);
	}

	public function upload_order(ExcelUploadRequest $request){
		try {
			$request->session()->forget('import_message');
		    Excel::import(new OrderImport, request()->file('file'));

		    if ($request->session()->get('import_message') == "yes") {
		    	session()->flash('notification-status', "danger");
		    	session()->flash('notification-msg', "Sorry, but the Excel File you're trying to upload has a duplicate. Please recheck the  Reference/Transaction/Serial Number Column. Don't worry, the other rows have been uploaded successfully. Please refresh the page to reflect the uploaded data.");
		    }else{
		    	session()->flash('notification-status', "success");
		    	session()->flash('notification-msg', "Imported Successfully. All the rows from the Excel has been uploaded successfully.. Please refresh the page to reflect the uploaded data.");
		    }
		
			return redirect()->route('system.order_transaction.pending');
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
		     $failures = $e->failures();
		     
		     foreach ($failures as $failure) {
		         $failure->row(); // row that went wrong
		         $failure->attribute(); // either heading key (if using heading row concern) or column index
		         $failure->errors(); // Actual error messages from Laravel validator
		         $failure->values(); // The values of the row that has failed.
		     }
		    session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Something went wrong.");
			return redirect()->route('system.order_transaction.pending');
		}
	}
	public function show(PageRequest $request,$id = NULL){

		$this->data['transaction'] = $request->get('order_transaction_data');
		$this->data['order_details'] = OrderDetails::where("transaction_number" , $this->data['transaction']->order_transaction_number)->get();
		$this->data['total_price'] = OrderDetails::where("transaction_number" , $this->data['transaction']->order_transaction_number)->sum('amount');

		$this->data['page_title'] = "Order Transaction Details";
		return view('system.order-transaction.show',$this->data);
	}

	public function paid(PageRequest $request, $id = NULL){
		DB::beginTransaction();
		try{
			$transaction = $request->get('order_transaction_data');
			$transaction->payment_type = "CASHIER";
			$transaction->payment_option = "CASHIER";
			$transaction->payment_method = "CASHIER";
			$transaction->payment_status = "PAID";
			$transaction->transaction_status = "COMPLETED";
			$transaction->payment_date = Carbon::now();
			$transaction->receipt_number = $request->get('receipt_number');
			$transaction->process_by = Auth::user()->id;
			$transaction->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Order Transaction has been successfully paid.");
			return redirect()->route('system.order_transaction.pending');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}

		

	}
}
