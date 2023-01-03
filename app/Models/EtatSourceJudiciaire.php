<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatSourceJudiciaire extends Model
{
    protected $table = 'etat_judiciaires';
    protected $fillable = ['admin_id', 'super_id','id_judiciaire','id_etat_infra'];
}
