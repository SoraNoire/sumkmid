<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';
    protected $fillable = ['*'];
}
