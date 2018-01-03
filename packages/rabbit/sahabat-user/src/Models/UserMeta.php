<?php

namespace Rabbit\SahabatUser\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserMeta extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_meta';
    protected $fillable = ['*'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
