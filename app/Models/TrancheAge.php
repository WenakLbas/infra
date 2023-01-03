<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrancheAge extends Model
{
    use HasFactory;
    protected $table = 'tranche_ages';
    protected $fillable = ['admin_id', 'super_id','libelle'];
}
