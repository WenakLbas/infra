<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner02 extends Model
{
    protected $table = 'partener02s';
    protected $fillable = ['admin_id', 'super_id', 'name', 'description', 'imgurl', 'link'];
}
