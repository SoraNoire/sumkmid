<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    protected $table = 'gallery_category';
    protected $fillable = ['name', 'slug', 'parent'];
}
