<?php

namespace App\Exports;

use App\Models\Infractions;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InfractionsExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Date du jour',
            'Localité',
            'Province',
            'Type d\'infraction',
            'Infractions',
        ];
    }

    public function collection()
    {
        return Infractions::select('date', 'localite', 'province', 'type_infraction', 'infractions')
            ->get();
    }
}
