<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon,Nexmo;

class SendReference extends Event {


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
			$ref_num = $value['ref_num'];

			$nexmo = Nexmo::message()->send([
				'to' => '+63'.(int)$phone,
				'from' => 'EOTCPH' ,
				'text' => "The transaction code for your application for EOTC-PH is #" . $ref_num . "\r\n\n Please enter this reference code on the EPAYMENT section on:\r\n www.eotcphp.ph to proceed.",
			]);
			
		}


		
		
		
	}
}
