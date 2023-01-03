<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Slider01 extends Model
{
    protected $table = 'slider01s';

    protected $fillable = ['admin_id', 'super_id', 'imgurl'];
}
