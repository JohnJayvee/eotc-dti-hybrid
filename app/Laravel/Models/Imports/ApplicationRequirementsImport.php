<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\ApplicationRequirements;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use Str, Helper, Carbon;

class ApplicationRequirementsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $index => $row) 
        {
            if($index == 0) {
                continue;
            }

            $is_exist = ApplicationRequirements::where('name',$row[0])->first();

            if (!$is_exist) {
                 $requirements = ApplicationRequirements::create([
                'name' => $row[0],
                'is_required' => strtolower($row[1]),
                ]);
               
                $requirements->save();
            }
           
           
        }
    }
}