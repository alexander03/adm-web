<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GobernanzaIrrigacion extends Model
{
    use SoftDeletes;
    protected $table = 'gobernanza-irrigacion';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
