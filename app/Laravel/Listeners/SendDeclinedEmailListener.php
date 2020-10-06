<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendDeclinedEmailReference;

class SendDeclinedEmailListener{

	public function handle(SendDeclinedEmailReference $email){
		$email->job();

	}
}