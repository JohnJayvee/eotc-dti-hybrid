<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendProcessorReference;

class SendProcessorReferenceListener{

	public function handle(SendProcessorReference $contact_number){
		$contact_number->job();

	}
}