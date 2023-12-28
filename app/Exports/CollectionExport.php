<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CollectionExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $results;

    public function __construct(Collection $results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'id',
            'deposit_name',
            'employee_id',
            'employee_name',
            'status',
            'mobile_no',
            'unit',
            'unit_id',
            'ord_no',
            'cutomer_name',
            'target_arrear',
            'target_current',
            'advanced_collection',
            'collection_amount',
            'hod_approved_status',
        ];
    }
}
