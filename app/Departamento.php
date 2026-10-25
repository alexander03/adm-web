<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento';
    protected $casts = ['deleted_at' => 'datetime'];

    /**
     * método para obtener las provincias hijas
     * @return [type] [description]
     */
    public function provincias()
	{
		return $this->hasMany('App\Provincia');
	}
}
