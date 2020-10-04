<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendProcessorTransaction;

class SendProcessorTransactionListener{

	public function handle(SendProcessorTransaction $contact_number){
		$contact_number->job();

	}
}