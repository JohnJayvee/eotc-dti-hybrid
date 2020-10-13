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

            if (!$is_exist) {
                 $department = Department::create([
                'name' => $row[0],
                
                ]);
               
                $department->save();
            }
           
           
        }
    }
}