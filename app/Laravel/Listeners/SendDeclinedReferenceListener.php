<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendDeclinedReference;

class SendDeclinedReferenceListener{

	public function handle(SendDeclinedReference $contact_number){
		$contact_number->job();

	}
}