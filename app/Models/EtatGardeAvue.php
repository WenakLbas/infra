<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatGardeAvue extends Model
{
    protected $table = 'etat_gardeavues';
    protected $fillable = ['admin_id', 'super_id','id_gardeavue','id_etat_infra'];
}
