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

        foreach ($array as $key => $value) {
            $details = OrderDetails::where('transaction_number' , $value->order_transaction_number)->get();
            $exist = OrderTransaction::where('order_transaction_number' ,$value->order_transaction_number)->first();

            if ($exist->is_email_send == 0) {
                $insert[] = [
                    'email' => $exist->email,
                    'contact_number' => $exist->contact_number,
                    'ref_num' => $exist->transaction_code,
                    'amount' => $exist->total_amount,
                    'full_name' => $exist->order->full_name,
                    'purpose' => $exist->order->purpose,
                    'sector' => $exist->order->sector,
                    'order_details' =>  $details,
                    'company_name' =>  $exist->company_name,
                    'created_at' => Helper::date_only($exist->created_at)
                ];  
                $notification_email_data = new SendOrderTransactionEmail($insert);
                Event::dispatch('send-email-order-transaction', $notification_email_data);
            }
            OrderTransaction::where('order_transaction_number',$array)->update(['is_email_send' => 1]);
        }
           
    }
}
