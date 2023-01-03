<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    protected $table = 'actualites';

    protected $fillable = ['admin_id', 'super_id', 'titre', 'slag', 'description', 'imgurl'];
}
