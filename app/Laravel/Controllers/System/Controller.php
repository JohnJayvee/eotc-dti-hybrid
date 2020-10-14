<?php

namespace App\Laravel\Controllers\System;

use App\Laravel\Controllers\Controller as BaseController;

use Auth, Session,Carbon, Helper,Route, Request,Str;

use  App\Laravel\Models\{AttendanceOvertime,AttendanceLeave,Transaction};

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
			'dashboard' => "system.dashboard"
		];
	}

	public function set_badges(){
		$auth = Auth::user();

		if($auth){
		$this->data['counter'] = [
			'pending' => Transaction::where('status','PENDING')->where('is_resent',0)->count(),
			'approved' => Transaction::where('status','APPROVED')->count(),
			'declined' => Transaction::where('status','DECLINED')->count(),
			'resent' => Transaction::where('status',"PENDING")->where('is_resent',1)->count()
			// 'pending_leave' =>  AttendanceLeave::where('employee_id',$auth->id)->where('status','for_approval')->count(),

			// 'all_pending_overtime' => AttendanceOvertime::where('status','for_approval')->count(),
			// 'all_pending_leave' => AttendanceLeave::where('status','for_approval')->count(),

		];

			// $this->data['counter']['for_approval'] = $this->data['counter']['pending_overtime']+$this->data['counter']['pending_leave'];

			// $this->data['counter']['all_pending'] = $this->data['counter']['all_pending_overtime']+$this->data['counter']['all_pending_leave'];
		}
		
	}

	public function set_suffixes(){
		$this->data['suffixes'] = [
			'' => "--Select Suffix--", 'JR' => "Jr/Junior", 'SR' => "Sr/Senior", 'I' => "I", 'II' => "II", 'III' => "III",'IV' => "IV",'V' => "V",'VI' => "VI",'VII' => "VII",'VIII' => "VIII",'IX' => "IX",'X' => "X",
		];
	}


	public function set_current_route(){
		 $this->data['current_route'] = Route::currentRouteName();
	}

	public function set_date_today(){
		$this->data['date_today'] = Helper::date_db(Carbon::now());
	}


	
}