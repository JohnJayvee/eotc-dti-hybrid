<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendApprovedReference;

class SendApprovedReferenceListener{

	public function handle(SendApprovedReference $contact_number){
		$contact_number->job();

	}
}