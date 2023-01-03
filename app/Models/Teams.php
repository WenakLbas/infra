<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $table = 'teams';
    protected $fillable = ['admin_id', 'super_id', 'name', 'fnction' , 'description', 'imgurl'];
}
