<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    protected $table = 'video_tag';
    protected $fillable = ['name', 'slug'];
}
