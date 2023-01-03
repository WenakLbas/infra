<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatSourceOsc extends Model
{
    protected $table = 'etat_oscs';
    protected $fillable = ['admin_id', 'super_id','id_osc','id_etat_infra'];
}
