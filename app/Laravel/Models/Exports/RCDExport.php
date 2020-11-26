<?php

namespace App\Laravel\Models\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Schedule;

use Helper,Str,Carbon,DB;

class  RCDExport implements WithEvents,FromCollection,WithMapping,WithHeadings,ShouldAutoSize
{
    use Exportable;

    public function __construct(Collection $transactions,$transaction_count,$per_type_total,$total_per_or)
    {
        $this->transactions = $transactions;
        $this->transaction_count = $transaction_count;
        $this->per_type_total = $per_type_total;
        $this->total_per_or = $total_per_or;
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
                $event->sheet->getStyle("A1:M1")->applyFromArray($styleTitulos);
                $this->filas = [];
                $this->limites = [];
                foreach ($this->transaction_count as $key => $value) {

                     array_push($this->limites, $value->count);
                    if ($key > 1) {
                        array_push($this->filas, $value->count + $this->filas[$key-1] + 1 );
                    }else{
                        array_push($this->filas, $value->count + array_sum($this->filas) + 1 );
                    }
                }
                foreach ($this->filas as $index => $fila){
                    $fila++;
                    $event->sheet->insertNewRowBefore($fila, 1);
                    
                    $event->sheet->getDelegate()->getStyle('A'.$fila.':'.$event->sheet->getDelegate()->getHighestColumn().$fila)
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff15');
                    $event->sheet->setCellValue("E{$fila}", "=SUM(E".($fila - $this->limites[$index]).":E".($fila - 1).")");
                    $event->sheet->setCellValue("F{$fila}", "=SUM(F".($fila - $this->limites[$index]).":F".($fila - 1).")");
                    $event->sheet->setCellValue("G{$fila}", "=SUM(G".($fila - $this->limites[$index]).":G".($fila - 1).")");
                    $event->sheet->setCellValue("H{$fila}", "=SUM(H".($fila - $this->limites[$index]).":H".($fila - 1).")");
                    $event->sheet->setCellValue("I{$fila}", "=SUM(I".($fila - $this->limites[$index]).":I".($fila - 1).")");
                    $event->sheet->setCellValue("J{$fila}", "=SUM(J".($fila - $this->limites[$index]).":J".($fila - 1).")");
                    $event->sheet->setCellValue("K{$fila}", "=SUM(K".($fila - $this->limites[$index]).":K".($fila - 1).")");
                    $event->sheet->setCellValue("L{$fila}", "=SUM(L".($fila - $this->limites[$index]).":L".($fila - 1).")");
                    $event->sheet->setCellValue("M{$fila}", "=SUM(M".($fila - $this->limites[$index]).":M".($fila - 1).")");
                    $event->sheet->setCellValue("D{$fila}","SUBTOTAL");
                    
                    
                }
                $event->sheet->setCellValue('D'. ($event->sheet->getHighestRow() + 1),"GRAND TOTAL");
                $event->sheet->setCellValue('E'. ($event->sheet->getHighestRow()), $this->total_per_or->amount_sum);
                 $event->sheet->getDelegate()->getStyle('A'.$event->sheet->getHighestRow().':'.$event->sheet->getDelegate()->getHighestColumn().$event->sheet->getHighestRow())
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');

                foreach ($this->per_type_total as $key => $value) {
                    if ($value->collection_type == "testing_fee") {
                        $event->sheet->setCellValue('G'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "gf_income") {
                        $event->sheet->setCellValue('F'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "ra") {
                        $event->sheet->setCellValue('H'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "iso_manuals") {
                        $event->sheet->setCellValue('I'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "pab") {
                        $event->sheet->setCellValue('J'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "ntf") {
                        $event->sheet->setCellValue('K'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "bid_securities") {
                        $event->sheet->setCellValue('L'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    if ($value->collection_type == "DST") {
                        $event->sheet->setCellValue('M'. ($event->sheet->getHighestRow()), $value->amount_sum);
                    }
                    
                   
                }
            }
        ];
    }
}