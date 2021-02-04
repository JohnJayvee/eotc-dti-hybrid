<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendOrderTransactionEmail extends Event {


	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data = $form_data;

		// $this->email = $form_data['insert'];

	

	}

	public function job(){	
		foreach($this->data as $index =>$value){
			$mailname = "Order Transaction Details";
			$user_email = $value['email'];
			$this->data['ref_num'] = $value['ref_num'];
			$this->data['amount'] = $value['amount'];
			$this->data['payor'] = $value['payor'];
			$this->data['created_at'] = $value['created_at'];
			$this->data['department'] = $value['department'];
			$this->data['order_details'] = $value['order_details']->toArray(); 

			$this->data['link'] = env("APP_URL");

			Mail::send('emails.order-transaction', $this->data, function($message) use ($mailname,$user_email){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("Order Transaction Details");
			});
		}
	}
}
