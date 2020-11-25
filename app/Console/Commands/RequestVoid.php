<?php

namespace App\Console\Commands;
use App\Laravel\Requests\PageRequest;
use Illuminate\Console\Command;
use App\Laravel\Models\Transaction;
use Carbon,Auth,DB,Str,ImageUploader,Event,FileUploader,PDF,QrCode,Helper,Curl,Log;
class RequestVoid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:requestvoid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PageRequest $request)
    {
           /* $request_body = [
                'subMerchantCode' => "EOTCPHP",
                'dateCreated' => "2020-11-13"
            ];  */


           $response = Curl::to("https://staging.digipepv2.ziapay.ph/api/transaction/inquire/void?subMerchantCode=EOTCPHP&dateCreated=2020-11-13")
                ->withHeaders( [
                    "X-token: ".env('DIGIPEP_TOKEN'),
                    "X-secret: ".env("DIGIPEP_SECRET")
                  ])
                 ->asJson( true )
                 ->returnResponseObject()
                 ->post();  
                
            if($response->status == "200"){
                $content = $response->content;
                $json_data = json_decode(json_encode($content));
                foreach ($json_data->data as $key => $value) {
                    $pf = Transaction::where('payment_reference',$value->transactionCode)->first();
                    $app = Transaction::where('application_payment_reference',$value->transactionCode)->first();
                    if ($pf) {
                        $pf->payment_status = strtoupper($value->status);
                        $pf->transaction_status = strtoupper($value->status);
                        $pf->save();
                    }
                    if ($app) {
                        $app->application_payment_status = strtoupper($value->status);
                        $app->application_transaction_status = strtoupper($value->status);
                        $app->save();
                    }
                }
            }
    }
}
