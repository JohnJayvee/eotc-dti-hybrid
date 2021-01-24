<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendOrderTransactionEmail;

class SendOrderTransactionListener{

	public function handle(SendOrderTransactionEmail $email){
		$email->job();

	}
}