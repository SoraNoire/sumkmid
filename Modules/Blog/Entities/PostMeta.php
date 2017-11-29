<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $table = 'post_meta';
    protected $fillable = ['*'];
}
