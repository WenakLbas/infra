<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner01 extends Model
{
    protected $table = 'partenaires';
    protected $fillable = ['admin_id', 'super_id', 'name', 'description', 'imgurl', 'link'];
}
