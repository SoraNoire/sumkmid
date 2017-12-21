<?php

namespace Rabbit\OAuthClient\Models;


use Illuminate\Database\Eloquent\Model;

class ModulePermissions extends Model
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
    
    public function module(){
        return $this->belongsTo('\Rabbit\OAuthClient\Models\Modules');
    }
}
