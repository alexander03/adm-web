<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ConstruccionIrrigacion extends Model
{
    use SoftDeletes;
    protected $table = 'construccion-irrigacion';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
