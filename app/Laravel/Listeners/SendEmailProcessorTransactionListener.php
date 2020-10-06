<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendEmailProcessorTransaction;

class SendEmailProcessorTransactionListener{

	public function handle(SendEmailProcessorTransaction $email){
		$email->job();
	}
}