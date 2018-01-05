<?php

namespace App\Http\Middleware;

use Closure;
use Modules\Blog\Entities\Option;

class MetaMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action
        app()->instance('Meta', new Meta);

        return $next($request);
    }
}

/**
* 
*/
class Meta
{
    protected $meta_title = 'Sahabat UMKM Indonesia';
    protected $gtm = '';
    protected $fb_pixel = '';
    protected $link_fb = '';
    protected $link_tw = '';
    protected $link_ig = '';
    protected $link_yt = '';        
    protected $link_in = '';
    protected $link_gplus = '';
    protected $footer_desc = '';
    protected $email_info = 'sekretariat@sahabatumkm.id';

    function __construct(){
        $this->gtm = Option::where('key', 'gtag_manager')->first()->value ?? '';
        $this->fb_pixel = Option::where('key', 'fb_pixel')->first()->value ?? '';
        $this->link_fb = Option::where('key', 'link_fb')->first()->value ?? '';
        $this->link_tw = Option::where('key', 'link_tw')->first()->value ?? '';
        $this->link_ig = Option::where('key', 'link_ig')->first()->value ?? '';
        $this->link_yt = Option::where('key', 'link_yt')->first()->value ?? '';        
        $this->link_in = Option::where('key', 'link_in')->first()->value ?? '';
        $this->link_gplus = Option::where('key', 'link_gplus')->first()->value ?? '';
        $this->footer_desc = Option::where('key', 'footer_desc')->first()->value ?? '';
        $this->email_info = Option::where('key', 'email')->first()->value ?? config('app.email_info');
    }

    public  function set($var, $value){
        $this->{$var} = $value;
    }

    public function get($var){
        return $this->{$var};
    }
}