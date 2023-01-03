<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaisnSatisfaction extends Model
{
    protected $table = 'raison_satisfactions';
    protected $fillable = ['admin_id', 'super_id','libelle','type_satisfaction'];
}
