<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $fillable = ['*'];
}
