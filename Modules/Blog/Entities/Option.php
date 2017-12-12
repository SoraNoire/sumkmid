<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'option';
    protected $fillable = ['key', 'value'];
}
