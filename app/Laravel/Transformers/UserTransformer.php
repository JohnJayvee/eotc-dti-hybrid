<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class UserTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];


	public function transform(User $user) {

	    return [
	     	'user_id' => $user->id,
	     	'name' => $user->name,
	     	'email_address' => $user->email,
	     	'gender' => "male"
	     ];
	}
}