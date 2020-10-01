<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\CitizenIdentification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CitizenIdentificationImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Tutorial' => new TutorialImport(),
            'Citizen List' => new CitizenImport(),
        ];
    }
}