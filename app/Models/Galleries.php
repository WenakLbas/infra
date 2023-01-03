<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galleries extends Model
{
    protected $table = 'galleries';

    protected $fillable = ['admin_id', 'super_id', 'imgurl', 'imgalt'];
}
