<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryTagRelation extends Model
{
    protected $table = 'gallery_tag_relation';
    protected $fillable = ['gallery_id', 'tag_id'];
}
