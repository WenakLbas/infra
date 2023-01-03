<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProfessionel extends Model
{
    protected $table = 'CategorieProfessionels';

    protected $fillable = ['admin_id', 'super_id', 'libelle_cat'];
}
