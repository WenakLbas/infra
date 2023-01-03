<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatInfraJour extends Model
{
    protected $table = 'etat_infra_jours';
    protected $fillable = [
        'admin_id			',
        'super_id			',
        'date_infra',
        'etat_denonciation',
        'etat_enquette',
        'etat_garde_avue',
        'etat_decision_final',
        'etat_non_enqte',
        'etat_satisfaction',
        'etat_source_judiciaire',
        'etat_source_osc',
        'etat_media_source',
        'id_infra',
        'id_victime',
        'id_age',
        'id_province',
        'id_lieu_infra',
    ];
}
