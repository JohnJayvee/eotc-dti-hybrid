<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendEmailProcessorReference extends Event {


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
			$mailname = "Account Details";
			$user_email = $value['email'];
			$this->data['otp'] = $value['otp'];
			$this->data['full_name'] = $value['full_name'];
			$this->data['ref_id'] = $value['ref_id'];
			$this->data['type'] = $value['type'];


			Mail::send('emails.processor-reference', $this->data, function($message) use ($mailname,$user_email){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("Account Details");
			});
		}


		
		
		
	}
}
