<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';
    protected $fillable = ['title', 'slug', 'images', 'author', 'status', 'option', 'published_at', 'updated_at', 'created_at'];
}
