<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaIrrigacion extends Model
{
    use SoftDeletes;
    protected $table = 'categoria-irrigacion';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
