<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $fillable = ['title', 'slug', 'body', 'featured_img', 'post_type', 'author', 'status', 'option', 'published_at'];
}
