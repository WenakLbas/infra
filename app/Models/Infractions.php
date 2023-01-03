<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infractions extends Model
{
    use HasFactory;
    protected $table = 'infras_imports';

    public $timestamps = false;

    protected $fillable = [
        'date',
        'localite',
        'province',
        'type_infraction',
        'infractions',
        'consquence',
        'source_information',
        'nom_media',
        'nom_osc',
        'nationalite',
        'genre',
        'categorie_prof',
        'tranche_age',
        'denonciation_verbale',
        'autorite_saisie',
        'plainte',
        'non_pourquoi',
        'enquete',
        'type_enquete',
        'diligente_par',
        'garde_vue',
        'duree_garde_vue',
        'jugement',
        'systeme_judiciaire',
        'etat_decision',
        'satisfait',
        'pourquoi_non_satisfait'
    ];
}
