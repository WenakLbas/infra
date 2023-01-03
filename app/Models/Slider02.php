<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider02 extends Model
{
    protected $table = 'slider02s';

    protected $fillable = ['admin_id', 'super_id', 'imgurl'];
}
