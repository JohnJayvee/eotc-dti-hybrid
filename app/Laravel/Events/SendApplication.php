<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendApplication extends Event {


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
			$mailname = "Application Details";
			$user_email = $value['email'];
			$this->data['name'] = $value['name'];
			$this->data['company_name'] = $value['company_name'];
			$this->data['department'] = $value['department'];
			$this->data['purpose'] = $value['purpose'];
			$this->data['ref_num'] = $value['ref_num'];
			Mail::send('emails.application', $this->data, function($message) use ($mailname,$user_email){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("Application Details");
			});
		}


		
		
		
	}
}
