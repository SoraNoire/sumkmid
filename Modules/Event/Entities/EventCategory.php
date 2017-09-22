<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $table = 'ev_category';
    protected $fillable = ['name', 'slug'];
}
