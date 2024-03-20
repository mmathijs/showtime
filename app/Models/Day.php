<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public $timestamps = false;

    protected $table = 'days';
    protected $guarded = [];
    protected $fillable = ['name', 'date'];
}
