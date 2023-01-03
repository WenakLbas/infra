<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatAutoriteSaisie extends Model
{
    protected $table = 'etat_autorite_saisies';
    protected $fillable = ['admin_id', 'super_id','id_autosa','id_etat_infra'];
}
