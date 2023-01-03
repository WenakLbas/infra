<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceMedia extends Model
{
    protected $table = 'source_medias';

    protected $fillable = ['admin_id', 'super_id', 'tmedia_id', 'rubrique', 'date'];
}
