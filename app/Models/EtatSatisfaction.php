<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatSatisfaction extends Model
{
    protected $table = 'etat_satisfactions';
    protected $fillable = ['admin_id', 'super_id','id_satifaction','id_etat_infra'];
}
