<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Victime extends Model
{
    protected $table = 'victimes';

    protected $fillable = [
        'admin_id			',
        'super_id			',
        'age',
        'id_proffession',
        'id_nationalite',
    ];
}
