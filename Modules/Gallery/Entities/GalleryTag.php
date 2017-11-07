<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryTag extends Model
{
    protected $table = 'gallery_tag';
    protected $fillable = ['name', 'slug'];
}
