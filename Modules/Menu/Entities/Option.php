<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'option';
    protected $fillable = ['name', 'value'];
}
