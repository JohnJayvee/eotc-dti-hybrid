<?php


namespace App\Laravel\Controllers\Web;

use App\Laravel\Requests\PageRequest;


use App\Laravel\Models\Transaction;
use App\Laravel\Events\SendCertificate;

use Helper, Carbon, Session, Str, DB,Input,Event,Signature,Curl,Log,PDF,Mail;
class DigipepController extends Controller
{	
	protected $data;

    public function __construct () {
		$this->data = [];
		parent::__construct();
		array_merge($this->data, parent::get_data());
	}

	public function success(PageRequest $request,$code = NULL){
		Log::info("Digipep Success",array($request->all()));
		$response = json_decode(json_encode($request->all()));
		if(isset($response->referenceCode)){
			$code = strtolower($response->referenceCode);
			$prefix = explode('-', $code)[0];

			switch (strtoupper($prefix)) {
				case 'APP':
					$transaction = Transaction::whereRaw("LOWER(transaction_code)  LIKE  '%{$code}%'")->first();
					break;
				
				default:
					$transaction = Transaction::whereRaw("LOWER(processing_fee_code)  LIKE  '%{$code}%'")->first();
					break;
			}

			if(!$transaction){
				Log::info("Digipep Record not found : {$response->referenceCode}");
				goto end;
			}

			$prefix = strtoupper($prefix);

			if(isset($response->payment) AND Str::upper($response->payment->status) == "PAID" AND $transaction->application_transaction_status != "COMPLETED" AND $prefix == "APP"){

				DB::beginTransaction();
				try{
					$transaction->application_payment_reference = $response->transactionCode;
					$transaction->application_payment_method  = $response->payment->paymentMethod;
					$transaction->application_payment_type  = $response->payment->paymentType;

					$transaction->application_payment_option  = "DIGIPEP";

					$transaction->application_payment_date = Carbon::now();
					$transaction->application_payment_status  = "PAID";
					$transaction->application_transaction_status  = "COMPLETED";
					$transaction->application_eor_url = $response->eorUrl;

					// $convenience_fee = $response->payment->convenienceFee;
					// $transaction->application_convenience_fee = $convenience_fee; 
					// $transaction->application_total_amount = $transaction->amount + $convenience_fee;
					$transaction->save();
					DB::commit();

					$insert_data[] = [
			            'email' => $transaction->email,
			            'full_name' => $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name,
			            'application_name' => $transaction->application_name,
			            'department_name' => $transaction->department_name,
			            'modified_at' => $transaction->modified_at,
			            'ref_num' => $transaction->transaction_code,
			            'link' => "http://54.251.82.120/certificate/".$transaction->id,
			        ];	

					$application_data = new SendCertificate($insert_data);
				    Event::dispatch('send-email-certificate', $application_data);

				}catch(\Exception $e){
					DB::rollBack();
					Log::alert("Digipep Error : "."Server Error. Please try again.".$e->getLine());
				}
			}

			if(isset($response->payment) AND Str::upper($response->payment->status) == "PAID" AND $transaction->transaction_status != "COMPLETED" AND $prefix == "PF"){

				DB::beginTransaction();
				try{
					$transaction->payment_reference = $response->transactionCode;
					$transaction->payment_method  = $response->payment->paymentMethod;
					$transaction->payment_type  = $response->payment->paymentType;

					$transaction->payment_option  = "DIGIPEP";

					$transaction->payment_date = Carbon::now();
					$transaction->payment_status  = "PAID";
					$transaction->transaction_status  = "COMPLETED";
					$transaction->eor_url = $response->eorUrl;
					// $convenience_fee = $response->payment->convenienceFee;
					// $transaction->convenience_fee = $convenience_fee; 
					// $transaction->total_amount = $transaction->processing_fee + $convenience_fee;
					$transaction->save();
					DB::commit();

				}catch(\Exception $e){
					DB::rollBack();
					Log::alert("Digipep Error : "."Server Error. Please try again.".$e->getLine());
				}
			}
			
		}

		end:
	}

	public function failed(PageRequest $request,$code = NULL){
		Log::info("Digipep Failed",array($request->all()));
		$response = json_decode(json_encode($request->all()));
		if(isset($response->referenceCode)){
			$code = strtolower($response->referenceCode);
			$prefix = explode('-', $code)[0];

			switch (strtoupper($prefix)) {
				case 'APP':
					$transaction = Transaction::whereRaw("LOWER(transaction_code)  LIKE  '%{$code}%'")->first();
					break;
				
				default:
					$transaction = Transaction::whereRaw("LOWER(processing_fee_code)  LIKE  '%{$code}%'")->first();
					break;
			}

			if(!$transaction){
				Log::info("Digipep Record not found : {$response->referenceCode}");
				goto end;
			}

			$prefix = strtoupper($prefix);

			if(isset($response->payment) AND Str::upper($response->payment->status) == "FAILED" AND $transaction->status != "COMPLETED" AND $prefix == "APP"){
			
				DB::beginTransaction();
				try{
					$transaction->application_payment_reference = $response->transactionCode;
					$transaction->application_payment_method  = $response->payment->paymentMethod;
					$transaction->application_payment_type  = $response->payment->paymentType;

					$transaction->application_payment_option  = "DIGIPEP";

					$transaction->application_payment_date = Carbon::now();
					$transaction->application_transaction_status  = "FAILED";
					$transaction->application_payment_status  = "UNPAID";
					$transaction->application_eor_url = $response->payment->eorURL;

					$convenience_fee = $response->payment->convenienceFee;
					$transaction->application_convenience_fee = $convenience_fee; 
					$transaction->application_total_amount = $transaction->amount + $convenience_fee;
					$transaction->save();
					DB::commit();

				}catch(\Exception $e){
					DB::rollBack();
					Log::alert("Digipep Error : "."Server Error. Please try again.".$e->getLine());
				}
			}

			if(isset($response->payment) AND Str::upper($response->payment->status) == "FAILED" AND $transaction->status != "COMPLETED" AND $prefix == "PF"){
			
				DB::beginTransaction();
				try{
					$transaction->payment_reference = $response->transactionCode;
					$transaction->payment_method  = $response->payment->paymentMethod;
					$transaction->payment_type  = $response->payment->paymentType;

					$transaction->payment_option  = "DIGIPEP";

					$transaction->payment_date = Carbon::now();
					$transaction->transaction_status  = "FAILED";
					$transaction->payment_status  = "UNPAID";
					$transaction->eor_url = $response->payment->eorURL;

					$convenience_fee = $response->payment->convenienceFee;
					$transaction->convenience_fee = $convenience_fee; 
					$transaction->total_amount = $transaction->processing_fee + $convenience_fee;
					$transaction->save();
					DB::commit();

				}catch(\Exception $e){
					DB::rollBack();
					Log::alert("Digipep Error : "."Server Error. Please try again.".$e->getLine());
				}
			}
		}

		end:
	}

	public function cancel(PageRequest $request,$code = NULL){
		// dd($request->all());
		Log::info("Digipep Cancel",array($request->all()));

		$code = strtolower($code);
		$prefix = explode('-', $code)[0];

		switch (strtoupper($prefix)) {
			case 'APP':
				$transaction = Transaction::whereRaw("LOWER(transaction_code)  LIKE  '%{$code}%'")->first();
				break;
			
			default:
				$transaction = Transaction::whereRaw("LOWER(processing_fee_code)  LIKE  '%{$code}%'")->first();
				break;
		}

		if(!$transaction){
			Log::info("Record not found : {$code}");
			goto end;
		}
		
		$prefix = strtoupper($prefix);

		if($transaction->status != "COMPLETED" AND $prefix == "APP") {
			$transaction->payment_date = Carbon::now();
			$transaction->application_transaction_status  = "CANCELLED";
			$transaction->save();
		}

		if($transaction->status != "COMPLETED" AND $prefix == "PF") {
			$transaction->payment_date = Carbon::now();
			$transaction->transaction_status  = "CANCELLED";
			$transaction->save();
		}

		session()->forget('transaction');
		session()->flash('notification-status',"failed");
		session()->flash('notification-msg',"Cancelled transaction.");
		return redirect()->route('web.main.index');

		end:
	}
}
