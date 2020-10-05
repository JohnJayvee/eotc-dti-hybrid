<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendEorUrl extends Event {


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
			$mailname = "EOR Details";
			$user_email = $value['email'];
			$this->data['full_name'] = $value['full_name'];
			$this->data['ref_num'] = $value['ref_num'];
			$this->data['eor_url'] = $value['eor_url'];

			Mail::send('emails.eorurl', $this->data, function($message) use ($mailname,$user_email){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("EOR Details");
			});
		}


		
		
		
	}
}
