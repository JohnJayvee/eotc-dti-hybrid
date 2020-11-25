<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendEmailProcessorReference;

class SendEmailProcessorReferenceListener{
	public function handle(SendEmailProcessorReference $email){
		$email->job();

	}
}