<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatDecisionFinal extends Model
{
    protected $table = 'etat_decisions';
    protected $fillable = ['admin_id', 'super_id','id_decifinal','id_etat_infra'];
}
