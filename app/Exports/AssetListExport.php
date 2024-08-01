<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetListExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection 
     */
    protected $all_headings = [];
    protected $field_list = [
        'name',
        'type_id',
        'image',
        'asset_code',
        'asset_serial_no',
        'is_working',
        'purchased_date',
        'warranty_available',
        'warranty_end_date',
        'is_available'
    ];

    public function collection()
    {
        return Asset::select($this->field_list)->with('type')->get()->map(function ($item) {
            $item->asset_type =  $item->type ? $item->type->name : 'N/A';
            $itemArray = $item->toArray();
            $itemArray = array_map(function ($value) {
                return is_string($value) ? ucwords(strtolower($value)) : $value;
            }, $itemArray);
            unset($itemArray['type_id'], $itemArray['type']);
            if (empty($this->all_headings)) {

                $this->all_headings = array_keys($itemArray);
            }
            Log::info("jyoti");
            return $itemArray;
        });
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFA0A0A0'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Auto-size columns
                foreach ($sheet->getColumnDimensions() as $columnDimension) {
                    $columnDimension->setAutoSize(true);
                }
            },
        ];
    }

    public function headings(): array
    {
        self::collection();
        return $this->all_headings;
    }
}
