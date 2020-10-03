<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\RegionalOfficeRequest;
/*
 * Models
 */
use App\Laravel\Models\RegionalOffice;

/*use App\Laravel\Models\Region;
use App\Laravel\Models\City;
use App\Laravel\Models\Province;*/

/* App Classes
 */
use Carbon,Auth,DB,Str;
class RegionalOfficeController extends Controller
{	
	protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		// $this->data['regions'] = ['' => '--Select Region--'] + Region::pluck('regDesc', 'regCode')->toArray();
		// $this->data['zone_types'] = ['' => "Choose Zone Type",'private_economic_zone' =>  "Private Economic Zone",'public_economic_zone' => "Public Economic Zone"];
		
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}	


    public function  index(PageRequest $request){
		$this->data['page_title'] = "Regional Office";
		$this->data['regional_offices'] = RegionalOffice::orderBy('created_at',"DESC")->get(); 
		return view('system.regional-office.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= "Regional Office - Add new record";
		
		return view('system.regional-office.create',$this->data);
	}

	public function store(RegionalOfficeRequest $request){
		
			$new_regional_office = new RegionalOffice;
			$new_regional_office->fill($request->all());
			$new_regional_office->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Regional Office has been added.");
			return redirect()->route('system.regional_office.index');
		
	}

	public function edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= "Regional Office - Edit record";
		$this->data['regional_office'] = $request->get('regional_office_data');

		return view('system.regional-office.edit',$this->data);
	}

	public function  update(RegionalOfficeRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$regional_office = $request->get('regional_office_data');
			$regional_office->fill($request->all());
			$regional_office->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Regional Office had been modified.");
			return redirect()->route('system.regional_office.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  destroy(PageRequest $request,$id = NULL){
		$regional_office = $request->get('regional_office_data');
		DB::beginTransaction();
		try{
			$regional_office->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Regional Office removed successfully.");
			return redirect()->route('system.regional_office.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	// public function get_region(PageRequest $request){
	// 	$id = $request->get('region_code');
	// 	$region = Region::pluck('regDesc', 'regCode');
	
	// 	$response['msg'] = "List of Cities";
	// 	$response['status_code'] = "PARAMETER_LIST";
	// 	$response['data'] = $region;
	// 	callback:

	// 	return response()->json($response, 200);

	// }


	// public function get_provinces(PageRequest $request){
	// 	$id = $request->get('region_code');
	// 	$provinces = Province::where('regCode',$id)->pluck('provDesc', 'provCode');
	
	// 	$response['msg'] = "List of Cities";
	// 	$response['status_code'] = "PARAMETER_LIST";
	// 	$response['data'] = $provinces;
	// 	callback:

	// 	return response()->json($response, 200);

	// }

	// public function get_municipalities(PageRequest $request){
	// 	$id = $request->get('province_code');
	// 	$cities = City::where('provCode',$id)->pluck('citymunDesc', 'citymunCode');
	
	// 	$response['msg'] = "List of Cities";
	// 	$response['status_code'] = "PARAMETER_LIST";
	// 	$response['data'] = $cities;
	// 	callback:
		
	// 	return response()->json($response, 200);
	// }
}
