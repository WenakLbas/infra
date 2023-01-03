<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProfession extends Model
{
    protected $table = 'type_professions';

    protected $fillable = ['admin_id', 'super_id', 'libelle_type_pr'];
}
