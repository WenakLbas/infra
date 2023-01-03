<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DureGardeAvue extends Model
{
    protected $table = 'gerdeavues';
    protected $fillable = ['admin_id', 'super_id', 'libelle'];
}
