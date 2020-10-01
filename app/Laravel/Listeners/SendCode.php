<?php 
namespace App\Laravel\Listeners;
use Nexmo;
class SendCode{

	public static function sendCode($phone){
		$code = rand(1111,9999);
		$nexmo = Nexmo::message()->send([
			'to' => '+63'.(int)$phone,
			'from' => 'EOTCPH' ,
			'text' => 'Verification Code ' . $code,
		]);
		return $code;

	}
}