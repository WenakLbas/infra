<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionFinal extends Model
{
    protected $table = 'decision_finals';
    protected $fillable = ['admin_id', 'super_id', 'libelle'];
}
