<?php

namespace App\Console\Commands;
use App\Laravel\Requests\PageRequest;
use Illuminate\Console\Command;
use App\Laravel\Models\{OrderTransaction,OrderDetails};
use App\Laravel\Events\SendOrderTransactionEmail;

use Carbon,Auth,DB,Str,ImageUploader,Event,FileUploader,PDF,QrCode,Helper,Curl,Log;

class SendMail extends Command
{
    

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendmail';

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
        $array = OrderTransaction::where('is_email_send' , 0)->take(30)->get();
        $data = [];
        foreach ($array as $key => $value) {
            array_push($data, $value->order_transaction_number);
            
        }

        foreach ($data as $key => $value) {
            Helper::email_send($value);
        }
    }
}
