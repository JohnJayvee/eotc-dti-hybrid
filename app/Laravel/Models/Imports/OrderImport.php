<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\{OrderDetails,OrderTransaction};

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use App\Laravel\Events\SendOrderTransactionEmail;

use Str, Helper, Carbon,Event;

class OrderImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);
        $len = count($rows);
        $transaction_number = [];
        foreach ($rows as $index => $row) 
        {  
            if($index == 0) {
                continue;
            }


            $is_exist = OrderDetails::where('order_id',$row[0])->first();
            if (!$is_exist and $row[23] != NULL) {
                $order_details = OrderDetails::create([
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
            }
            
        }
        

    }
}