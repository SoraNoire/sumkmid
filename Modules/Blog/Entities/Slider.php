<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';
    protected $fillable = ['image', 'title', 'btn_text', 'link'];
}
