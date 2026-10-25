<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AnalisisTrasvase extends Model
{
    use SoftDeletes;
    protected $table = 'analisis-trasvase';
    protected $casts = ['deleted_at' => 'datetime'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function categoria()
    {
        return $this->belongsTo('App\CategoriaIrrigacion', 'categoria_id');
    }
}
