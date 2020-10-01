<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\CitizenIdentification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TutorialImport implements ToCollection
{
    public function collection(Collection $rows)
    {

    }
}