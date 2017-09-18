<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'page';
    protected $fillable = ['title', 'slug', 'body', 'featured_img', 'author', 'status', 'option', 'published_at'];
}
