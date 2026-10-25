<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogImagen extends Model
{
    use SoftDeletes;
    protected $table = 'blog-imagen';
    protected $casts = ['deleted_at' => 'datetime'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function blog()
    {
        return $this->belongsTo('App\Blog', 'blog_id');
    }

    
}
