<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatNonEnqte extends Model
{
    protected $table = 'etat_non_enqtes';
    protected $fillable = ['admin_id', 'super_id','id_rsnon_enqte','id_etat_infra'];
}

