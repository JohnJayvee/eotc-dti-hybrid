<?php

namespace App\Laravel\Models\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Schedule;

use Helper,Str,Carbon;

class  RCDExport implements WithEvents,FromCollection,WithMapping,WithHeadings,ShouldAutoSize
{
    use Exportable;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function headings(): array
        {
            return [
                'Transaction Date',
                'Responsibility Code',
                'Payor',
                "Particulars",
                "Total per OR",
                "GF Income",
                "Testing Fee",
                "RA",
                "PNS/ ISO  Manuals",
                "PAB",
                "NTF",
                "Bid Securities",
                "DST",
            ];
        }

    public function map($value): array
    {   
        $string = "100000100001000";
        return [
            Helper::date_format($value->created_at),
            $value->department ? $value->department->code : "N/A",
            $value->company_name,
            $value->type ? Strtoupper($value->type->name) : "N/A",
            $value->processing_fee,
            $value->type->collection_type == "gf_income" ? $value->processing_fee : " ",
            $value->type->collection_type == "testing_fee" ? $value->processing_fee : " ",
            $value->type->collection_type == "ra" ? $value->processing_fee : " ",
            $value->type->collection_type == "iso_manuals" ? $value->processing_fee : " ",
            $value->type->collection_type == "pab" ? $value->processing_fee : " ",
            $value->type->collection_type == "ntf" ? $value->processing_fee : " ",
            $value->type->collection_type == "bid_securities" ? $value->processing_fee : " ",
            $value->type->collection_type == "dst" ? $value->processing_fee : " ",



        ];
    }



    public function collection()
    {
        return $this->transactions;
    }

    public function registerEvents(): array
    {
    return [
        
        AfterSheet::class => function(AfterSheet $event) {
            $highest_row = $event->sheet->getHighestRow()+1;
            $sum = count($this->transactions) + 1;
            $event->sheet->setCellValue('F'. ($highest_row), '=SUM(F2:F'.$sum.')');
            $event->sheet->setCellValue('G'. ($highest_row), '=SUM(G2:G'.$sum.')');
            $event->sheet->setCellValue('H'. ($highest_row), '=SUM(H2:H'.$sum.')');
            $event->sheet->setCellValue('I'. ($highest_row), '=SUM(I2:I'.$sum.')');
            $event->sheet->setCellValue('J'. ($highest_row), '=SUM(J2:J'.$sum.')');
            $event->sheet->setCellValue('K'. ($highest_row), '=SUM(K2:K'.$sum.')');
            $event->sheet->setCellValue('L'. ($highest_row), '=SUM(L2:L'.$sum.')');
            $event->sheet->setCellValue('M'. ($highest_row), '=SUM(M2:M'.$sum.')');

        },
    ];
}
}