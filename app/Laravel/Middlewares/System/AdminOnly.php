<?php 

namespace App\Laravel\Middlewares\System;

use Closure,Auth;
use Illuminate\Contracts\Auth\Guard;

class AdminOnly {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
    {

    	$auth = Auth::user();
    	
		if(in_array($auth->type,['user','finance','marketing'])){
			abort(401);
		}
    	

        return $next($request);
    }

}
