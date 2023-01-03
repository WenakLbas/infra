<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FSI extends Model
{
    protected $table = 'fsis';

    protected $fillable = ['admin_id', 'super_id', 'struct_securitaire', 'type_post', 'fonction_fsi'];
}
