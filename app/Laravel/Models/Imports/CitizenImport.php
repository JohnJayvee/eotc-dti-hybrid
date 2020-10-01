<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\CitizenIdentification;
use App\Laravel\Models\Citizen;
use App\Laravel\Models\Barangay;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use Str, Helper, Carbon;

class CitizenImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $index => $row) 
        {
            $identification = null;
            if($index == 0 || is_null($row[2])) {
                continue;
            }

            $citizen = Citizen::where('code', $row[0])->first();

            if($citizen) {
                $identification = CitizenIdentification::where('citizen_id', $citizen->id)->whereIn('status', ['PENDING', 'APPROVED'])->first();
            } else {
                $identification = CitizenIdentification::where('fname', strtoupper($row[2]))->where('lname', strtoupper($row[4]))->where('civil_status', strtoupper($row[9]))->whereIn('status', ['PENDING', 'APPROVED'])->first();
            }

            if($identification){
                continue;
            }

            $brgy_name = Str::upper($row[16]);
            $brgy = Barangay::whereRaw("UPPER(name) = '{$brgy_name}'")->first();

            $citizen = CitizenIdentification::create([
                'citizen_id' => $citizen ? $citizen->id : 0,
                'referral_code' => Str::random(6),
                'census_id' => $row[1],
                'fname' => strtoupper($row[2]),
                'mname' => strtoupper($row[3]),
                'lname' => strtoupper($row[4]),
                'suffix' => strtoupper($row[5]),
                'email' => $row[7],
                'contact_number' => $row[8],
                'civil_status' => strtoupper($row[9]),
                'is_pwd' => strtolower($row[10]),
                'is_senior' => strtolower($row[11]),
                'is_registered_voter' => strtolower($row[12]),
                'is_resident' => strtolower($row[13]),
                'is_indigent' => strtolower($row[14]),
                'residence_street' => $row[15],
                'residence_brgy' => $brgy ? Str::upper($brgy->name) : null,
                'residence_district' => $brgy ? Str::lower($brgy->district) : null,
                'permanent_street' => strtoupper($row[17]),
                'permanent_brgy' => strtoupper($row[18]),
                'permanent_city' => strtoupper($row[19]),
                'permanent_province' => strtoupper($row[20]),
                'gross_income' => $row[21],
                'sss_gsis' => $row[22],
                'tin_id' => $row[23],
                'source_of_funds' => $row[24],
                'source_of_funds_other' => $row[25],
                'status' => 'APPROVED',
            ]);

            $date_fields = ['birthdate'];
            foreach($date_fields as $index => $field){
                $citizen->{"{$field}"} = NULL;
                if(!is_null($row[6])){
                    $citizen->{"{$field}"} = Helper::date_db(Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])));
                } else {
                    $citizen->status = 'PENDING';
                }
            }

            if(!$brgy){
                $citizen->status = 'PENDING';
            }

            $citizen->official_id = "RES-".str_pad($citizen->id, 4, "0", STR_PAD_LEFT);
            $citizen->save();

            $citizen->official_id = "ID-".(Carbon::now()->format("ymd")).str_pad($citizen->id, 4, "0", STR_PAD_LEFT);
            $citizen->save();

            // dd($citizen);
        }
    }
}