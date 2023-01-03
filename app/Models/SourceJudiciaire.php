<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceJudiciaire extends Model
{
    protected $table = 'source_judiciaires';
    protected $fillable = ['admin_id', 'super_id', 'fonction'];
}
