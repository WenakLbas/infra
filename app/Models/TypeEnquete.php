<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEnquete extends Model
{
    protected $table = 'type_enquetes';

    protected $fillable = ['admin_id', 'super_id', 'libelle'];
}
