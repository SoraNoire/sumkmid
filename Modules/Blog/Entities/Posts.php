<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'nposts';
    protected $fillable = ['*'];
}
