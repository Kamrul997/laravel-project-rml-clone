<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ColectionExport implements FromCollection, WithHeadings
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
            'Serial',
            'Name',
        ];
    }
}
