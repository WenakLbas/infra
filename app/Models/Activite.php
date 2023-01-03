<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{ protected $table = 'activites';
    protected $fillable = ['admin_id', 'super_id', 'titre','date','heure','lieu'];
}