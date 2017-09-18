<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    protected $table = 'media';
    protected $fillable = ['name'];
}
