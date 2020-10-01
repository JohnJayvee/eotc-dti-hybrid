<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendReference;

class SendReferenceListener{

	public function handle(SendReference $contact_number){
		$contact_number->job();

	}
}