<?php

namespace App\Laravel\Middlewares\Api;

use Closure, Helper,Str;
use App\Laravel\Models\{Citizen,CitizenIdentification};


class NotRegisteredResident
{

    protected $format;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $record
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->format = $request->format;
        $response = array();

        $citizen = auth('citizen')->user();

        $application = CitizenIdentification::where('citizen_id',$citizen->id)->whereIn('status',['approved','pending'])->first();

        if($application){
            if($application->status == "pending"){
                $response = [
                    'msg' => "Unable to do another application. Your application still on-process.",
                    'status' => FALSE,
                    'status_code' => "ON_PROCESS",
                ];
            }else{
                $response = [
                    'msg' => "Already applied and have an approved application.",
                    'status' => FALSE,
                    'status_code' => "ALREADY_REGISTERED",
                ]; 
            }
        }

        if(empty($response)) {
            return $next($request);
        }

        callback:
        switch(Str::lower($this->format)){
            case 'json' :
                return response()->json($response, 406);
            break;
            case 'xml' :
                return response()->xml($response, 406);
            break;
        }
    }

}