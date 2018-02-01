<?php

namespace App\Http\Middleware;

use Closure;
use Modules\Blog\Entities\Option;
use App\Helpers\PublicHelper;

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
    protected $meta_site_name = 'Sahabat UMKM';
    protected $meta_title = '';
    protected $meta_desc = '';
    protected $meta_keyword = '';
    protected $meta_type = 'website';
    protected $meta_url = '';
    protected $meta_image = '';
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
    protected $meta = array();
    protected $top_menu = '';

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

        $meta = Option::where('key', 'home_metas')->first()->value ?? '';
        if ($meta != '') {
            $meta = json_decode($meta);
        }
        $this->meta_title = $meta->title ?? 'Sahabat UMKM';
        $this->meta_desc = $meta->desc ?? '';
        $this->meta_keyword = $meta->keyword ?? '';
        $this->meta_image = asset('img/sahabat-umkm-logo-200x200.png');

        $this->meta_url = url()->current();
        $this->meta = array('title' => $this->meta_title,
                             'description' => $this->meta_desc,
                             'keyword' => $this->meta_keyword,
                             'og:type' => $this->meta_type,
                             'og:title' => $this->meta_title,
                             'og:description' => $this->meta_desc,
                             'og:url' => $this->meta_url,
                             'og:image' => $this->meta_image,
                             'og:image:alt' => $this->meta_title,
                             'og:site_name' => $this->meta_site_name,
                             'twitter:card' => 'summary',
                             'twitter:description' => $this->meta_desc,
                             'twitter:title' => $this->meta_title,
                             'twitter:url' => $this->meta_url,
                             'twitter:image:src' => $this->meta_image
                            );
        $this->top_menu = PublicHelper::print_top_menu();
    }

    public  function set($var, $value){
        if ($value != '') {
            if (is_array($var) && sizeof($var) > 0) {
                foreach ($var as $key => $var) {
                    $this->{$var} = $value;
                }
            } else {
                $this->{$var} = $value;   
            }
        }
    }

    public function get($var){
        return $this->{$var};
    }

    public function print_meta($useprefix=true){
        $this->meta = array('title' => $this->meta_title,
                             'description' => $this->meta_desc,
                             'keyword' => $this->meta_keyword,
                             'og:type' => $this->meta_type,
                             'og:title' => $this->meta_title,
                             'og:description' => $this->meta_desc,
                             'og:url' => $this->meta_url,
                             'og:image' => $this->meta_image,
                             'og:image:alt' => $this->meta_title,
                             'og:site_name' => $this->meta_site_name,
                             'twitter:card' => 'summary',
                             'twitter:description' => $this->meta_desc,
                             'twitter:title' => $this->meta_title,
                             'twitter:url' => $this->meta_url,
                             'twitter:image:src' => $this->meta_image
                            );
        $element = '';
        foreach ($this->meta as $key => $value) {
            if (strpos($key, 'twitter') !== false) {
                $element .= "<meta name='$key' content='$value'>\n";   
            } else if (strpos($key, ':') !== false) {
                $element .= "<meta property='$key' content='$value'>\n";   
            } else {
                $element .= "<meta name='$key' content='$value'>\n";   
            }   
        }
        return $element;
    }
}