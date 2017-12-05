<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'file';
    protected $fillable = ['name', 'label'];
}
