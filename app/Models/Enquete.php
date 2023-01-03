<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquete extends Model
{
    protected $table = 'enquetes';

    protected $fillable = ['admin_id', 'super_id', 'titre', 'slag', 'description', 'imgurl'];
}
