<?php

namespace App\Laravel\Controllers\Web;

use App\Laravel\Controllers\Controller as BaseController;

use Auth, Session,Carbon, Helper,Route, Request,Str;

use  App\Laravel\Models\{AttendanceOvertime,AttendanceLeave};

class Controller extends BaseController{

	protected $data;

	public function __construct(){
		self::set_system_routes();
		self::set_date_today();
		self::set_current_route();
		self::set_badges();
		self::set_auth();

	}

	public function get_data(){
		$this->data['page_title'] = env("APP_NAME","");
		return $this->data;
	}

	public function set_auth(){
		$this->data['auth'] = Auth::user();
	}

	public function set_system_routes(){
		$this->data['routes'] = [
			'homepage' => "web.homepage"
		];
	}

	public function set_badges(){
		$auth = Auth::user();

		if($auth){
			$this->data['counter'] = [
				// 'pending_overtime' => AttendanceOvertime::where('employee_id',$auth->id)->where('status','for_approval')->count(),
				// 'pending_leave' =>  AttendanceLeave::where('employee_id',$auth->id)->where('status','for_approval')->count(),

				// 'all_pending_overtime' => AttendanceOvertime::where('status','for_approval')->count(),
				// 'all_pending_leave' => AttendanceLeave::where('status','for_approval')->count(),

			];
			
		}
		
	}


	public function set_current_route(){
		 $this->data['current_route'] = Route::currentRouteName();
	}

	public function set_date_today(){
		$this->data['date_today'] = Carbon::now()->format(env('CONFIG_DATE_DB',"Y-m-d"));
	}


	
}