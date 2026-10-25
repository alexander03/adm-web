<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Binnacle extends Model
{
    protected $table = 'binnacle';
    protected $casts = ['deleted_at' => 'datetime'];
}
