<?php

namespace Rabbit\OAuthClient\Models;


use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    var $timestamps  = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function modulepermission(){
        return $this->hasMany('\Rabbit\OAuthClient\Models\ModulePermissions', 'module_id', 'id');
    }
}
