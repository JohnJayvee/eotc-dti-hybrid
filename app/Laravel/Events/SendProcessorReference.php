<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon,Nexmo;

class SendProcessorReference extends Event {


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
			$phone = $value['contact_number'];
			$otp = $value['otp'];
			$full_name = $value['full_name'];
			$ref_id = $value['ref_id'];
			$type = $value['type'];

			$nexmo = Nexmo::message()->send([
				'to' => '+63'.(int)$phone,
				'from' => 'EOTCPH' ,
				'text' => "Congratulations, ".$full_name."! Your ". str::title($type) ." has successfully created your account. You may use this account credentials to log in to your Processor Portal.\r\n\n Processor Portal link: http://54.251.82.120/\r\nReference number: ".$ref_id."\r\n One-time-password (OTP):".$otp."\r\n\n If you didn't request this or believe that you received this in error, please ignore this SMS.",
			]);
			
		}


		
		
		
	}
}
