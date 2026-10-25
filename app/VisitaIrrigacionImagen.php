<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitaIrrigacionImagen extends Model
{
    use SoftDeletes;
    protected $table = 'visitas-irrigacion-imagen';
    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function visitairrigacion()
    {
        return $this->belongsTo('App\VisitaIrrigacion', 'visita_id');
    }

    
}
