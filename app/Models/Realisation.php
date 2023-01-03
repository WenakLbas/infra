<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisation extends Model
{
    protected $table = 'realisations';
    protected $fillable = ['admin_id', 'super_id', 'nombre', 'description', 'status'];
}
