<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $table = 'acts';
    public $timestamps = false;
    protected $fillable = ['type', 'name', 'day', 'start_time', 'display_type', 'people', 'material_required', 'current'];
}
