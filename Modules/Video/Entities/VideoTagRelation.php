<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Model;

class VideoTagRelation extends Model
{
    protected $table = 'video_tag_relation';
    protected $fillable = ['video_id', 'tag_id'];
}
