<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Blog\Entities\PostMeta;
use Modules\Blog\Entities\Option;

class PublicHelper
{
    /**
     * Get Post from MNEWS
     * @return array
     *
     */
    public static function getMNewsPosts()
    {
        $curl = new \anlutro\cURL\cURL;
        $mnews_url = config('app.mnews_url') ?? 'http://news.mdirect.id';
        $curl_response = $curl->get($mnews_url.'/get-sahabat-umkm-post');
        $posts = [];
        if ($curl_response->info['content_type'] == 'application/json') {
            $posts = json_decode($curl_response->body);
        }

        if (count($posts) > 0) {
            foreach ($posts as $key => $value) {
                $prop = $value->properties;
                $prop = json_decode($prop);
                $value->meta_title = $prop->meta_title;
                $value->post_desc = $prop->meta_desc;
                $value->meta_keyword = $prop->meta_keyword;
                $value->link = url('read/'.$value->kategori_slug.'/'.$value->slug_id); 
                if ($value->post_desc == '') {
                    $value->post_desc = str_limit(html_entity_decode(strip_tags($value->post)), 250);
                }
            }
        }

        return $posts;
    }

    /**
     * Get Post Meta
     * @return array
     *
     */
    public static function get_post_meta($id){
        $post_metas = PostMeta::where('post_id',$id)->get();

        $metas = new \stdClass;;
        foreach ($post_metas as $key => $value) {
            $metas->{$value->key} = $value->value;
        }

        $data = [];

        $data['event_type']   = $metas->event_type ?? '';
        $data['event_url']    = $metas->event_url ?? '';
        $data['gmaps_url']    = $metas->gmaps_url ?? '';
        $data['location']     = $metas->location ?? '';
        $htm = $metas->htm ?? '';
        if (is_string($htm) && $htm != 'free') {
            $htm = json_decode($htm);
        }
        $data['htm'] = $htm;
        $data['open_at']      = $metas->open_at ?? '';
        $data['closed_at']    = $metas->closed_at ?? '';
        $data['meta_desc']    = $metas->meta_desc ?? '';
        $data['meta_title']   = $metas->meta_title ?? '';
        $data['meta_keyword'] = $metas->meta_keyword ?? '';
        $mentor_reg      = json_decode($metas->mentor_registered ?? '') ?? [];
        $mentor_not_reg      = json_decode($metas->mentor_not_registered ?? '') ?? [];

        $mentors = array();
        if (sizeof($mentor_reg) > 0) {
            foreach ($mentor_reg as $mentor_r) {
                $users = app()->OAuth->mentors($mentor_r)->users;
                if (sizeof($users) > 0) {
                    $mentors[] = $users[0];
                }
            }
        }
        if (sizeof($mentor_not_reg) > 0) {
            foreach ($mentor_not_reg as $mentor_nr) {
                $tmp = json_encode(['name' => $mentor_nr]);
                $mentors[] = json_decode($tmp);
            }
        }
        $data['mentors'] = $mentors;
        return $data;
    }

    /**
     * Print Top Menu
     * @return array
     *
     */
    public static function print_top_menu(){
        $menu = '<li><a href="'.url("/").'">Beranda</a></li>';
        $menu_position = Option::where('key', 'menu_position')->first()->value ?? '';
        if ($menu_position != '') {
            $menu = '';
            $menu_position = json_decode($menu_position);

            foreach ($menu_position as $item) {
                $menu .= '<li><a href="'.$item->link.'">'.$item->label.'</a>';
            }
        }
        return $menu;
    }
}