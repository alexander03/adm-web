<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ResponsabilidadIrrigacion extends Model
{
    use SoftDeletes;
    protected $table = 'responsabilidad-irrigacion';
    protected $casts = ['deleted_at' => 'datetime'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
