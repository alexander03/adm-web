<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CarruselIrrigacion extends Model
{
    use SoftDeletes;
    protected $table = 'carrusel-irrigacion';
    protected $casts = ['deleted_at' => 'datetime'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
