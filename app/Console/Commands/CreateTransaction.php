<?php

namespace App\Console\Commands;
use App\Laravel\Requests\PageRequest;
use Illuminate\Console\Command;
use App\Laravel\Models\{OrderTransaction,OrderDetails};
use App\Laravel\Events\SendOrderTransactionEmail;

use Carbon,Auth,DB,Str,ImageUploader,Event,FileUploader,PDF,QrCode,Helper,Curl,Log;

class CreateTransaction extends Command
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
        $data= OrderDetails::where('created_transaction', 0)->take(100)->get();

        if ($data) {
            foreach ($data as $key => $value) {
            $sum_amount = OrderDetails::where('transaction_number' , $value->transaction_number)->sum('amount');

            OrderTransaction::firstOrCreate(
                ['order_transaction_number' => $value->transaction_number],
                [
                    'payor' => $value->payor , 
                    'email' => $value->email,
                    'contact_number' => $value->contact_number,
                    'department' => $value->department_code,
                    'payment_category' => $value->payment_category,
                    'total_amount' => $sum_amount,
                    'transaction_code' => 'OT-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($value->order_id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3))
                ]);

                OrderDetails::where('transaction_number',$value->transaction_number)->update(["created_transaction" => "1"]);
            }
        }
    }
}
