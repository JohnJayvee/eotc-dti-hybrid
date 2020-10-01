<?php 

namespace App\Laravel\Controllers\Api;

use App\Laravel\Requests\PageRequest;

use App\Laravel\Models\User;


use App\Laravel\Requests\Api\EmployeeRequest;

use App\Laravel\Transformers\TransformerManager;
use App\Laravel\Transformers\UserTransformer;

use Carbon,Auth,DB,Str;

class EmployeeController extends Controller{

	protected $response = [];
	protected $response_code;

	public function __construct(){
		$this->response = array(
			"msg" => "Bad Request.",
			"status" => FALSE,
			'status_code' => "BAD_REQUEST"
			);
		$this->response_code = 400;
		$this->transformer = new TransformerManager;
	}

	public function  index(PageRequest $request,$format = NULL){

		// $employees = Employee::all();

		$employee = new User;
		$employee->id = 1;
		$employee->name = "hello world";
		$employee->email = "fake@email.com";

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "EMPLOEE_LIST";
		$this->response['msg'] = "List of employees.";
		$this->response['data'] = $this->transformer->transform($employee,new UserTransformer,"item");

		$this->response_code = 200;

		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}	
	}
	
	public function store(EmployeeRequest $request,$format = NULL){

		$new_employee = new User;
		$new_employee->email = Str::lower($request->get('email'));
		$new_employee->name = $request->get('name');
		// $new_employee->save();


		$this->response['status'] = TRUE;
		$this->response['status_code'] = "EMPLOYEE_ADDED";
		$this->response['msg'] = "New employee added.";
		$this->response['data'] = $this->transformer->transform($new_employee,new UserTransformer,"item");

		$this->response_code = 201;

		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}	
	}

	public function update(EmployeeRequest $request,$format = NULL){
		$employee = $request->get('employee_data');

		// $employee = User::find($request->get('employee_id'));
		$employee->email = Str::lower($request->get('email'));
		$employee->name = $request->get('name');
		// $employee->save();

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "EMPLOYEE_UPDATE";
		$this->response['msg'] = "Employee modified.";
		$this->response['data'] = $this->transformer->transform($employee,new UserTransformer,"item");

		$this->response_code = 200;

		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}	
	}

	public function destroy(PageRequest $request, $format = NULL){
		$employee = $request->get('employee_data');
		// $employee->delete();
		$this->response['status'] = TRUE;
		$this->response['status_code'] = "EMPLOYEE_DELETED";
		$this->response['msg'] = "Employee has been removed.";
		$this->response_code = 200;

		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}	
	}
		
}