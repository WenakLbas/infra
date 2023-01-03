<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $table = 'presentations';

    protected $fillable = ['admin_id', 'super_id', 'titre', 'slag', 'description', 'imgurl'];
}
