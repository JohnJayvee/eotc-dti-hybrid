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

		$this->data['order_transactions'] = OrderTransaction::with('order')->where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(order_transaction_number)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if (strlen($this->data['auth']->type) > 0) {
						return $query->where('department',$this->data['auth']->type);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_payment_status']) > 0){
						return $query->where('payment_status',$this->data['selected_payment_status']);
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

			
		    Excel::import(new OrderImport, request()->file('file'));

		    session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Importing data was successful.");
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
		$this->data['total_price'] = OrderDetails::where("transaction_number" , $this->data['transaction']->order_transaction_number)->sum('price');

		$this->data['page_title'] = "Order Transaction Details";
		return view('system.order-transaction.show',$this->data);
	}
}
