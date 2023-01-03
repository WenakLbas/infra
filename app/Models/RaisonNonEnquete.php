<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaisonNonEnquete extends Model
{
    protected $table = 'rsnonenqtes';
    protected $fillable = ['admin_id', 'super_id','libelle'];
}
