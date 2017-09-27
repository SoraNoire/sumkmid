<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventForumRelation extends Model
{
    protected $table = 'ev_forum_relation';
    protected $fillable = ['event_id', 'forum_id'];
}
