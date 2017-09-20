<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Model;

class VideoCategoryRelation extends Model
{
    protected $table = 'video_category_relation';
    protected $fillable = ['video_id', 'category_id'];
}
