<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendEorUrl;

class SendEorUrlListener{

	public function handle(SendEorUrl $email){
		$email->job();

	}
}