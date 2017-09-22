<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    protected $table = 'event';
    protected $fillable = ['title', 'slug', 'description', 'featured_img', 'event_type', 'forum_id', 'mentor_id', 'location', 'htm', 'option', 'status', 'open_at', 'closed_at', 'published_at'];
}
