<?php

namespace App\Imports;

use App\Models\Infractions;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InfractionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Infractions([
            'date' => $row['date'],
            'localite' => $row['localite'],
            'province' => $row['province'],
            'type_infraction' => $row['type_infraction'],
            'infractions' => $row['infractions'],
            'consquence' => $row['consquence'],
            'source_information' => $row['source_information'],
            'nom_media' => $row['nom_media'],
            'nom_osc' => $row['nom_osc'],
            'nationalite' => $row['nationalite'],
            'genre' => $row['genre'],
            'categorie_prof' => $row['categorie_prof'],
            'tranche_age' => $row['tranche_age'],
            'denonciation_verbale' => $row['denonciation_verbale'],
            'autorite_saisie' => $row['autorite_saisie'],
            'plainte' => $row['plainte'],
            'non_pourquoi' => $row['non_pourquoi'],
            'enquete' => $row['enquete'],
            'type_enquete' => $row['type_enquete'],
            'diligente_par' => $row['diligente_par'],
            'garde_vue' => $row['garde_vue'],
            'duree_garde_vue' => $row['duree_garde_vue'],
            'jugement' => $row['jugement'],
            'systeme_judiciaire' => $row['systeme_judiciaire'],
            'etat_decision' => $row['etat_decision'],
            'satisfait' => $row['satisfait'],
            'pourquoi_non_satisfait' => $row['pourquoi_non_satisfait'],
        ]);
    }
}
