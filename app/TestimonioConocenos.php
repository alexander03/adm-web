<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TestimonioConocenos extends Model
{
    use SoftDeletes;
    protected $table = 'testimonio-conocenos';
    protected $casts = ['deleted_at' => 'datetime'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
