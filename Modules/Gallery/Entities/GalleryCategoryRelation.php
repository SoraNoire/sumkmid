<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryCategoryRelation extends Model
{
    protected $table = 'gallery_category_relation';
    protected $fillable = ['gallery_id', 'category_id'];
}
