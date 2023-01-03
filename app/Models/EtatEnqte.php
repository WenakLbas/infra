<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatEnqte extends Model
{
    protected $table = 'etat_enqtes';
    protected $fillable = ['admin_id', 'super_id', 'id_autosaiaie', 'id_etat_infra', 'id_enqte'];
}
