<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LinkLinea extends Model
{
    use SoftDeletes;
    protected $table = 'link-linea';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
