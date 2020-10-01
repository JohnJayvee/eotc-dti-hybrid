<?php

namespace App\Laravel\Middlewares\Api;

use Closure, Helper,Str;
use App\Laravel\Models\{TravelHistory,Article};
use App\Laravel\Models\{AccountCode};


class ExistRecord
{

    protected $format;
    protected $reference_id;

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
        $this->format = $request->format;
        $response = array();

        switch (strtolower($record)) {
            case 'travel':
                $this->reference_id = $request->get('travel_id');
                if(! $this->__exist_travel($request)) {
                    $response = [
                        'msg' => "Travel History not found.",
                        'status' => FALSE,
                        'status_code' => "HISTORY_NOT_FOUND",
                        'hint' => "Make sure the 'travel_id' from your request parameter exists and valid."
                    ];
                }
            break;

            case 'article':
                $this->reference_id = $request->get('article_id');
                if(! $this->__exist_article($request)) {
                    $response = [
                        'msg' => "Travel History not found.",
                        'status' => FALSE,
                        'status_code' => "HISTORY_NOT_FOUND",
                        'hint' => "Make sure the 'article_id' from your request parameter exists and valid."
                    ];
                }
            break;
            case 'available_reference_code':
                $this->reference_id = $request->get('reference_code');
                if(! $this->__exist_available_reference_code($request)) {
                    $response = [
                        'msg' => "Reference Code no longer available.",
                        'status' => FALSE,
                        'status_code' => "NOT_AVAILABLE",
                        'hint' => "Make sure the 'reference_code' from your request parameter exists and valid."
                    ];
                }
            break;

            case 'reference_code':
                $this->reference_id = $request->get('reference_code');
                if(! $this->__exist_reference_code($request)) {
                    $response = [
                        'msg' => "Reference Code not found.",
                        'status' => FALSE,
                        'status_code' => "CODE_NOT_FOUND",
                        'hint' => "Make sure the 'reference_code' from your request parameter exists and valid."
                    ];
                }
            break;
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

    private function __exist_reference_code($request){
        $code = Str::upper($this->reference_id);
        $reference_code = AccountCode::whereRaw("UPPER(reference_code) = '{$code}'")->first();

        if($reference_code){
            $request->merge(['account_code_data' => $reference_code]);
            return TRUE;
        }

        return FALSE;
    }

    private function __exist_available_reference_code($request){
        $account_code = $request->get('account_code_data');

        if($account_code AND $account_code->status == "available"){
            return TRUE;
        }

        return FALSE;
    }


    private function __exist_travel($request){
        $travel = TravelHistory::find($this->reference_id);

        if($travel){
            $request->merge(['travel_data' => $travel]);
            return TRUE;
        }

        return FALSE;
    }

    private function __exist_article($request){
        $article = Article::find($this->reference_id);

        if($article){
            $request->merge(['article_data' => $article]);
            return TRUE;
        }

        return FALSE;
    }
}