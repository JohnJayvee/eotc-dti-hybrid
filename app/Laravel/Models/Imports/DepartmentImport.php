<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\Department;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use Str, Helper, Carbon;

class DepartmentImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $index => $row) 
        {
            if($index == 0) {
                continue;
            }

            $is_exist = Department::where('name',$row[0])->first();
            $is_exist_code = Department::where('code',$row[1])->first();
            if (!$is_exist || !$is_exist_code) {
                 $department = Department::create([
                'name' => $row[0],
                'code' => $row[1],
                
                ]);
               
                $department->save();
            }
           
           
        }
    }
}