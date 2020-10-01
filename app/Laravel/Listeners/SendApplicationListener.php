<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendApplication;

class SendApplicationListener{

	public function handle(SendApplication $email){
		$email->job();

	}
}