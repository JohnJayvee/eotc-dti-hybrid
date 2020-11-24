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

use Helper,Str,Carbon,DB;

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
        $this->transaction_count = DB::table('transaction')
                            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
                            ->groupBy('date')
                            ->get();
        


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
        $styleTitulos = [
        'font' => [
            'bold' => true,
            'size' => 12
        ]
        ];
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Sistema de alquileres');
            },
            AfterSheet::class => function(AfterSheet $event) use ($styleTitulos){
                $event->sheet->setCellValue('A'. ($event->sheet->getHighestRow()+1),"Total");
                $this->filas = [];

                foreach ($this->transaction_count as $key => $value) {

                    
                    if ($key > 1) {
                        array_push($this->filas, $value->count + $this->filas[$key-1] + 1 );
                    }else{
                        array_push($this->filas, $value->count + array_sum($this->filas) + 1 );
                    }
                }
                foreach ($this->filas as $index => $fila){
                    $fila++;
                    $event->sheet->insertNewRowBefore($fila, 1);
                    
                }
            }
        ];
    }
}