<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendCertificate;

class SendCertificateListener{

	public function handle(SendCertificate $email){
		$email->job();

	}
}