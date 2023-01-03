<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoriteSaisie extends Model
{
    protected $table = 'autoritesaisies';
    protected $fillable = [
        'admin_id',
        'super_id',
        'titre',
        'abilite_recevoire_plainte',
        'abilite_recevoire_denociation',
        'abilite_ouvrir_enquete',
        'sys_judiciare_appartenance'
    ];
}
