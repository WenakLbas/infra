<?php

namespace App\Exports;

use App\Models\Infractions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CrimeExport implements FromCollection, ShouldAutoSize, WithHeadingRow, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Infractions::orderBy('date', 'desc')
            ->select('infras_imports.*',)
            ->where(['type_infraction' => 'crime'])
            ->get();
    }

    public function headings():array {
        return [
            'ID                    ',
            'Date                    ',
            'Localité                ',
            'Provinces               ',
            'Type d\'Infraction       ',
            'Infraction              ',
            'Consquence   ',
            'Source de l\'information ',
            'Nom media               ',
            'Nom de l\'OSC',
            'Nationalite',
            'Genre',
            'Categorie Professionnelle',
            'Tranche d\'age',
            'Denonciation verbale',
            'Autorité saisie',
            'Plainte',
            'Non pourquoi',
            'Enquete',
            'Type Enquete',
            'Qui a diligenté l’enquête',
            'Garde a  vue',
            'Durée  garde a vue',
            'Jugement',
            'Système Judiciaire',
            'Etat décision',
            'Pourquoi satisfait',
            'Pourquoi non satisfait'

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:P1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
            $styleArray = [
                'A1:P1',
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FFFF0000'],
                    ],
                ],
            ],

        ];
    }
}
