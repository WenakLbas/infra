<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatAutoriteDenonciation extends Model
{
    protected $table = 'etat_denonciations';
    protected $fillable = ['admin_id', 'super_id','id_autosaisie','id_etat_infra'];
}
