<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatMediaSource extends Model
{
    protected $table = 'etat_media_sources';
    protected $fillable = ['admin_id', 'super_id','id_media_source','id_etat_infra'];
}
