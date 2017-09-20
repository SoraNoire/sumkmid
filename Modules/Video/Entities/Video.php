<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';
    protected $fillable = ['title', 'slug', 'body', 'video_url', 'featured_img', 'post_type', 'author', 'status', 'option', 'published_at'];
}
