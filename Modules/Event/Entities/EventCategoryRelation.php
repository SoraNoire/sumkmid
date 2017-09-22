<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventCategoryRelation extends Model
{
    protected $table = 'ev_category_relation';
    protected $fillable = ['event_id', 'category_id'];
}
