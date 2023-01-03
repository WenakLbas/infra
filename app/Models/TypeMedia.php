<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMedia extends Model
{
    protected $table = 'type_medias';

    protected $fillable = ['admin_id', 'super_id', 'libelle', 'description'];
}
