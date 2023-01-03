<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuInfra extends Model
{
    protected $table = 'lieu_infras';
    protected $fillable = ['admin_id', 'super_id', 'libelle', 'infra_id'];
}
