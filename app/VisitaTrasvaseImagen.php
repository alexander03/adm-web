<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitaTrasvaseImagen extends Model
{
    use SoftDeletes;
    protected $table = 'visitas-trasvase-imagen';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function visitatrasvase()
    {
        return $this->belongsTo('App\VisitaTrasvase', 'visita_id');
    }

    
}
