<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = ['admin_id', 'super_id', 'infra_id', 'actus_id', 'enq_id', 'link'];
}
