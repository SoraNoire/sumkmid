<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventMentorRelation extends Model
{
    protected $table = 'ev_mentor_relation';
    protected $fillable = ['event_id', 'mentor_id'];
}
