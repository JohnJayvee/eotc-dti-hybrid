<?php

namespace App\Laravel\Middlewares\System;

use Closure, Helper,Str;
use App\Laravel\Models\{Department,ApplicationType,Application,User,Transaction,RegionalOffice,ApplicationRequirements};

use App\Laravel\Models\{AccountCode};

class ExistRecord
{

    protected $reference_id;
    protected $module;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $record
     * @return mixed
     */
    public function handle($request, Closure $next, $record)
    {
        $this->reference_id = $request->id;
        $module = "dashboard";
        $found_record = true;
        $previous_route = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
        switch (strtolower($record)) {

            case 'transaction':
                if(! $this->__exist_transaction($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "transaction.index";
                }
            break;

            case 'application':
                if(! $this->__exist_application($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "application.index";
                }
            break;
            case 'department':
                if(! $this->__exist_department($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "department.index";
                }
            break;
            case 'processor':

                if(! $this->__exist_processor($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "processor.index";
                }
            break;
            case 'regional-office':
                if(! $this->__exist_regional_office($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "zone-location.index";
                }
            break;
            case 'requirements':
                if(! $this->__exist_requirements($request)) {
                    $found_record = false;
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "No record found or resource already removed.");

                    $module = "application-requirements.index";
                }
            break;
            
        }

        if($found_record) {
            return $next($request);
        }
        no_record_found:
        return redirect()->route("system.{$module}");
    }

    private function __exist_transaction($request){
        $transaction = Transaction::find($this->reference_id);
        if($transaction){
            $request->merge(['transaction_data' => $transaction]);
            return TRUE;
        }

        return FALSE;
    }

    private function __exist_department($request){
        $department = Department::find($this->reference_id);

        if($department){
            $request->merge(['department_data' => $department]);
            return TRUE;
        }

        return FALSE;
    }

    private function __exist_application($request){
        $application= Application::find($this->reference_id);

        if($application){
            $request->merge(['application_data' => $application]);
            return TRUE;
        }

        return FALSE;
    }

    private function __exist_processor($request){
        $processor= User::find($this->reference_id);

        if($processor){
            $request->merge(['processor_data' => $processor]);
            return TRUE;
        }

        return FALSE;
    }
    private function __exist_regional_office($request){
        $office= RegionalOffice::find($this->reference_id);

        if($office){
            $request->merge(['regional_office_data' => $office]);
            return TRUE;
        }

        return FALSE;
    }
    private function __exist_requirements($request){
        $requirements= ApplicationRequirements::find($this->reference_id);

        if($requirements){
            $request->merge(['requirement_data' => $requirements]);
            return TRUE;
        }

        return FALSE;
    }

}