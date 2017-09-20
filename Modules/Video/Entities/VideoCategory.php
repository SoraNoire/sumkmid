<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $table = 'video_category';
    protected $fillable = ['name', 'slug', 'parent'];
}
