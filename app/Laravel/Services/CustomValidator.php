<?php 

namespace App\Laravel\Services;

use Illuminate\Validation\Validator;
// use App\Laravel\Models\{IPAddress,Employee,AttendanceOvertime,EmployeeLeaveCredit,AttendanceLeave};
use App\Laravel\Models\{User,Citizen,Barangay};
use App\Laravel\Models\{AccountCode};
use App\Laravel\Models\{Application,ApplicationRequirements};


use Auth, Hash,Str,Carbon,Helper,Request;

class CustomValidator extends Validator {

    public function validateWithCount($attribute,$value,$parameters){

        if(is_array($parameters) AND isset($parameters[0])){ $file = Request::get($parameters[0]); }
        if(is_array($parameters) AND isset($parameters[0])){ $application_id = Request::get($parameters[1]); }

        $application = Application::find($application_id);
        $requirements_count = ApplicationRequirements::whereIn('id', explode(",",$application->requirements_id))->where('is_required',"yes")->count();
        
        if ($file < $requirements_count) {
            return FALSE;
        }
        return TRUE;
            
    }
    public function validateMinimumAmount($attribute, $rule, $parameters){

        $value = $this->getValue($attribute);
     

        if(is_array($parameters) AND isset($parameters[0])){ $application_id = Request::get($parameters[0]); }
        if(is_array($parameters) AND isset($parameters[0])){ $partial_amount = Request::get($parameters[1]); }
       

        $application = Application::find($application_id);
        $amount = $application->partial_amount ?: 0;
        
        if ($amount >= $partial_amount) {
            return TRUE;
        }

            return FALSE;
       
    }

    protected function replaceMinimumAmount($message,$attribute, $rule, $parameters)
    {
     
        if(is_array($parameters) AND isset($parameters[0])){ $application_id = Request::get($parameters[0]); }
        if(is_array($parameters) AND isset($parameters[0])){ $partial_amount = Request::get($parameters[1]); }
       
        $application = Application::find($application_id);
        $amount = $application->partial_amount ?: 0;
        $custom_message = "The amount you entered exceeded the allowed partial amount. allowed Partial Amount is PHP ".$amount;
        if ($amount == 0) {
           $custom_message = "This Application doesn't allowed partial payment";
        }
        
        return str_replace(':message', $custom_message, $message);
    }

    protected function replaceWithLeave($message, $attribute, $rule, $parameters)
    {
        $value = $this->getValue($attribute);
        $custom_message = "error message.";
        $employee_id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $end_date = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "0";
        $type = (is_array($parameters) AND isset($parameters[2]) ) ? $parameters[2] : "0";

        if(!$type) $custom_message = "Please select a leave type.";
        $available_credit = 0;
        $used_credit = AttendanceLeave::where('employee_id',$employee_id)->where('type',$type)->whereIn('status',['approved','for_approval'])->count();
        $with_credit = EmployeeLeaveCredit::where('employee_id',$employee_id)->where('type',$type)->where('status','active')->first();
        if($with_credit) $available_credit = $with_credit->num_credit;

        if(!$available_credit) $custom_message = "All ".Helper::nice_display($type)." credits are already used.";
        $available_credit -= $used_credit;
        $start = Carbon::parse($value);
        $end = Carbon::parse($end_date);
        $diffInDays = $start->diffInDaysFiltered(function(Carbon $date) {
                    return !$date->isWeekend();
        }, $end) + 1;

        if($diffInDays > $available_credit) $custom_message = "Over Filing...You only have {$available_credit} ".Helper::nice_display($type)." credits available but you're filing for {$diffInDays} days leave.";


        return str_replace(':message', $custom_message, $message);
    }

    public function validateValidReferenceCode($attribute,$value,$parameters){
        return  AccountCode::where('reference_code',$value)
                        ->where('status','available')
                        ->count() ? TRUE : FALSE;
    }
    public function validateIsRequired($attribute,$value,$parameters){
        if(is_array($parameters) AND isset($parameters[0])){ $application_id = Request::get($parameters[0]); }
        if(is_array($parameters) AND isset($parameters[1])){ $file_count = $parameters[1]; }
        $application =  Application::where('id',$application_id)->first();
        
        $requirements = ApplicationRequirements::whereIn('id',explode(",", $application->requirements_id))->where('is_required',"yes")->count();

        if ($file_count < $requirements) {

            return FALSE;
        }else{

            return TRUE;
        }

    }

    public function validateValidBrgy($attribute,$value,$parameters){
        $brgy = Str::upper($value);
        return  Barangay::whereRaw("UPPER(name) = '{$brgy}'")
                        ->count() ? TRUE : FALSE;
    }

    

    public function validateWithLeave($attribute,$value,$parameters){
        $employee_id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $end_date = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "0";
        $type = (is_array($parameters) AND isset($parameters[2]) ) ? $parameters[2] : "0";

        if(!$type) return FALSE;

        $available_credit = 0;
        $used_credit = AttendanceLeave::where('employee_id',$employee_id)->where('type',$type)->whereIn('status',['approved','for_approval'])->count();

        $with_credit = EmployeeLeaveCredit::where('employee_id',$employee_id)->where('type',$type)->where('status','active')->first();
        if($with_credit) $available_credit = $with_credit->num_credit;
        $available_credit -= $used_credit;

        if(!$available_credit) return FALSE;

        $start = Carbon::parse($value);
        $end = Carbon::parse($end_date);
        $diffInDays = $start->diffInDaysFiltered(function(Carbon $date) {
                    return !$date->isWeekend();
        }, $end)+1;

        if($diffInDays > $available_credit) return FALSE;

        return TRUE;
    }

    public function validateUniqueLeave($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $employee_id = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "0";

        return EmployeeLeaveCredit::where('employee_id',$employee_id)
                                    ->where('type',$value)
                                    ->where('id','<>',$id)
                                    ->where('status',['active'])
                                    ->count() ? FALSE : TRUE;
    }

    public function validateUniqueOvertime($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $employee_id = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "0";

        $date = Carbon::parse($value)->format("Y-m-d");

        return AttendanceOvertime::where('employee_id',$employee_id)
                                    ->where('login_date',$date)
                                    ->where('id','<>',$id)
                                    ->whereNotIn('status',['disapproved','declined','cancelled'])
                                    ->count() ? FALSE : TRUE;

    }

    public function validateUniqueEmployeeNumber($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";

        return  Employee::where('employee_number',$value)
                        ->where('id','<>',$id)
                        ->count() ? FALSE : TRUE;
    }

    public function validateUniqueIpRecord($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";

        return IPAddress::where('ip',$value)
                        ->where('id','<>',$id)
                        ->count() ? FALSE : TRUE;

    }

    public function validateUniqueUsername($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $type = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "user";

        switch (Str::lower($type)) {
            case 'citizen':
                return  Citizen::where('username',Str::lower($value))
                                ->where('id','<>',$id)
                                ->count() ? FALSE : TRUE;
            break;
            
            default:
                return  User::where('username',Str::lower($value))
                                ->where('id','<>',$id)
                                ->count() ? FALSE : TRUE;
        }
        
    }

    public function validateUniqueEmail($attribute,$value,$parameters){
        $id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "0";
        $type = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "user";

        switch (Str::lower($type)) {
            case 'citizen':
                return  Citizen::where('email',Str::lower($value))
                                ->where('id','<>',$id)
                                ->count() ? FALSE : TRUE;
            break;
            
            default:
                return  User::where('email',Str::lower($value))
                                ->where('id','<>',$id)
                                ->count() ? FALSE : TRUE;
        }
        
    }



    public function validateCurrentPassword($attribute, $value, $parameters){
        $account_id = (is_array($parameters) AND isset($parameters[0]) ) ? $parameters[0] : "";
        $account_type = (is_array($parameters) AND isset($parameters[1]) ) ? $parameters[1] : "user";


        if($account_type == "citizen"){
            $user_id = $parameters[0];
            $user = Citizen::find($user_id);
            return Hash::check($value,$user->password);
        }else{
            $user_id = $parameters[0];
            $user = User::find($user_id);
            return Hash::check($value,$user->password);
        }

        return FALSE;
    }

    public function validateOldPassword($attribute, $value, $parameters){
        
        if($parameters){
            $user_id = $parameters[0];
            $user = User::find($user_id);
            return Hash::check($value,$user->password);
        }

        return FALSE;
    }

    public function validatePasswordFormat($attribute,$value,$parameters){
    	return preg_match(("/^(?=.*)[A-Za-z\d!@#$%^&*()_+.<>]{6,20}$/"), $value);
    }

    public function validateUsernameFormat($attribute,$value,$parameters){
    	return preg_match(("/^(?=.*)[a-zA-Z\d][a-z\d._+]{6,20}$/"), $value);
    }

} 