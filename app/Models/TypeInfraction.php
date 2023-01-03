<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeInfraction extends Model
{
    protected $table = 'type_infractions';

    protected $fillable = ['admin_id', 'super_id', 'libelle'];
}
