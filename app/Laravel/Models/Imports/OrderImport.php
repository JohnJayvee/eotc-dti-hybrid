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
       
        foreach ($rows as $index => $row) 
        {  
            if($index == 0) {
                continue;
            }

            $is_exist = OrderDetails::where('order_id',$row[0])->first();
            $is_exist_tn = OrderDetails::where('transaction_number',$row[3])->first();
            if ($is_exist and $is_exist_tn) {
                session()->put('import_message',"yes");
            }
            if (!$is_exist and $row[10] != NULL and !$is_exist_tn) {
                    switch ($row[1]) {
                        case "BPSLIBRARY":
                            $code = "bps_library_admin";
                            break;
                        case 'PCIMS':
                            $code = "pcims_admin";
                            break;
                        case "BPSTESTINGCENTER":
                            $code = "bps_testing_admin";
                            break;
                        default:
                            $code = "admin";
                            break;
                    }
                $order_details = OrderDetails::create([
                    $date = intval($row[2]),    
                    'order_id' => $row[0],
                    'department_code' => $code,
                    'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d'),
                    'transaction_number' => $row[3],
                    'payor' => $row[4],
                    'address' => $row[5],
                    'contact_number' => $row[6],
                    'email' => $row[7],
                    'particulars' => $row[8],
                    'payment_category' => $row[9],
                    'amount' => $row[10],
                    'payment_status' => $row[11],
                ]);
                $order_details->save();
            }
            
        }
        

    }
}