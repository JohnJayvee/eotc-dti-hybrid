<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\Barangay;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class BarangayTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];


	public function transform(Barangay $brgy) {

	    return [
	     	'brgy_id' => $brgy->id,
	     	'psgc_brgy' => $brgy->psgc_brgy,
	     	'brgy_name' => $brgy->name,
	     	'district' => $brgy->district,
	     ];
	}
}