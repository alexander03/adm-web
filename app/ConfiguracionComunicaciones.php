<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ConfiguracionComunicaciones extends Model
{
    use SoftDeletes;
    protected $table = 'configuracion-comunicaciones';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
