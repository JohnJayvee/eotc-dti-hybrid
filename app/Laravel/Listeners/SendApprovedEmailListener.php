<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendApprovedEmailReference;

class SendApprovedEmailListener{

	public function handle(SendApprovedEmailReference $email){
		$email->job();

	}
}