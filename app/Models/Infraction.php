<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infraction extends Model
{
    protected $table = 'infractions';

    protected $fillable = ['admin_id', 'super_id', 'libelle_inf', 'date'];
}
