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
    protected $signature = 'command:createtransaction';

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
        $data= OrderDetails::where('created_at', '>=', Carbon::now()->subMinutes(30)->toDateTimeString())->take(100)->get();

        if ($data) {
            foreach ($data as $key => $value) {
            $sum_amount = OrderDetails::where('transaction_number' , $value->transaction_number)->sum('price');
            OrderTransaction::firstOrCreate(
                ['order_transaction_number' => $value->transaction_number],
                [
                    'fname' => $value->first_name , 
                    'mname' => $value->middle_name,
                    'lname' => $value->last_name , 
                    'company_name' => $value->company_name , 
                    'email' => $value->email,
                    'contact_number' => $value->tel_no,
                    'total_amount' => $sum_amount,
                    'transaction_code' => 'OT-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($value->order_id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3))
                ]);
            }
        }
    }
}
