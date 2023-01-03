<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OSC extends Model
{
    protected $table = 'oscs';

    protected $fillable = ['admin_id', 'super_id', 'nom', 'description', 'date'];
}
