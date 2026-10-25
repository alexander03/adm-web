<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
	 use SoftDeletes;
    protected $table = 'rol';
    protected $casts = ['deleted_at' => 'datetime'];
}
