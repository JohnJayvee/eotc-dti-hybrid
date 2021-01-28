<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\{OrderDetails,OrderTransaction};

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;

use App\Laravel\Events\SendOrderTransactionEmail;

use Str, Helper, Carbon,Event;

class OrderImport implements WithEvents,ToModel, WithChunkReading, ShouldQueue,WithStartRow
{   
    use Importable, RegistersEventListeners;
   
    public function registerEvents(): array
    {
        return [
            
            AfterImport::class => [self::class, 'afterImport'],
                           
        ];
    }
    public function model(array $row)
    {  
        $is_exist = OrderDetails::where('order_id',$row[0])->first();
        if (!$is_exist and $row[23] != NULL) {
            $order_details = new OrderDetails([
                $date = intval($row[1]),
                'order_id' => $row[0],
                'request_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d'),
                'transaction_number' => $row[2],
                'designation_number' => $row[3],
                'no_of_pages' => $row[4],
                'no_of_copies' => $row[5],
                'company_name' => $row[6],
                'order_title' => $row[7],
                'title' => $row[8],
                'first_name' => $row[9],
                'middle_name' => $row[10],
                'last_name' => $row[11],
                'email' => $row[12],
                'tel_no' => $row[13],
                'unit_no' => $row[14],
                'street_name' => $row[15],
                'brgy' => $row[16],
                'municipality' => $row[17],
                'province' => $row[18],
                'region' => $row[19],
                'zip_code' => $row[20],
                'sector' => $row[21],
                'purpose' => $row[22],
                'price' => $row[23],
            ]);
            $order_details->save();

            $order_transaction = OrderTransaction::where('order_transaction_number' , $row[2])->first();
            if(!$order_transaction){
                $new_order = new OrderTransaction();
                $new_order->order_transaction_number = $row[2];
                $new_order->fname = $row[9];
                $new_order->mname = $row[10];
                $new_order->lname = $row[11];
                $new_order->company_name = $row[6];
                $new_order->email = $row[12];
                $new_order->contact_number = $row[13];
                $new_order->company_name = $row[6];
                $new_order->save();
                $new_order->transaction_code =  'OT-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($new_order->id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));
                $new_order->save();
               
           }
        }
    }
    public function startRow(): int 
    {
         return 2;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
    public static function afterImport(AfterImport $event) 
    {
        //OrderTransaction::Test();
    }

  
}