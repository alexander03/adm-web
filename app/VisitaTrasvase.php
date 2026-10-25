<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VisitaTrasvase extends Model
{
    use SoftDeletes;
    protected $table = 'visitas-trasvase';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
