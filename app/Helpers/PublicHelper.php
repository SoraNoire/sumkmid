<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Blog\Entities\PostMeta;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\Option;
use DB;
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

    public static function readMetas($arr=[]){
        $metas = new \stdClass;;
        foreach ($arr as $key => $value) {
            $metas->{$value->key} = $value->value;
        }
        return $metas;
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

    /**
     * Print Section Title
     */
    public static function print_section_title($title){
        $split = explode(' ', $title);
        $split[count($split)-1] = "</span><span>".$split[count($split)-1]."</span>";
        $split[0] = "<span>".$split[0];
        $output = implode(" ", $split);
        return $output;
    }

    public static function listUsaha(){
        $usaha = [
                    'Aplikasi Dan Pengembang Permainan',
                    'Arsitektur',
                    'Desain Interior',
                    'Desain Komunikasi Visual',
                    'Desain Produk',
                    'Fashion',
                    'Film, Animasi, Dan Video',
                    'Fotografi',
                    'Kriya',
                    'Kuliner',
                    'Musik',
                    'Penerbitan',
                    'Periklanan',
                    'Seni Pertunjukan',
                    'Seni Rupa',
                    'Televisi Dan Radio'
            ];
        return (object)$usaha;
    }

    /**
     * Schema Detail Website
     */
    public static function SchemaWebSite() {
        $homepage = route('public_home');
        $linkSearch = route('search_gallery').'?q={search_term}';

        $schema =  '<script type="application/ld+json">
                    { "@context" : "http://schema.org",
                      "@type" : "WebSite",
                      "url" : "'.$homepage.'",';
        
        //potentialAction
        $schema .= '"potentialAction" : {';
        $schema .= '"@type" : "SearchAction",
                    "target" : "'.$linkSearch.'",
                    "query-input" : "required name=search_term"
                    }';

        $schema .= '}</script>';

        return $schema;
    }

    /**
     * Schema Organization
     */
    public static function SchemaOrganization(){
        $profile['fb'] = Option::where('key','link_fb')->first()->value;
        $profile['tw'] = Option::where('key','link_tw')->first()->value;
        $profile['ig'] = Option::where('key','link_ig')->first()->value;
        $profile['in'] = Option::where('key','link_in')->first()->value;
        $profile['yt'] = Option::where('key','link_yt')->first()->value;

        $legalName = 'Sahabat UMKM';
        $logo = asset('images/sbt-icon.png');
        $homepage = route('public_home');
        $contactType = 'customer service';
        $telephone = '+62213917399';

        $schema =  '<script type="application/ld+json">{
                        "@context" : "http://schema.org",
                        "@type" : "Organization",
                        "name" : "'.$legalName.'",
                        "legalName" : "'.$legalName.'",
                        "url" : "'.$homepage.'",
                        "logo" : "'.$logo.'",
                        "contactPoint" : [{
                        "@type" : "ContactPoint",
                        "telephone" : "'.$telephone.'",
                        "contactType" : "'.$contactType.'"
                    }],
                      "sameAs" : [';
        $i = 0;
        $len = count($profile);
        foreach($profile as $key => $value) {
        $schema .= '"'.$value.'"';
        if ($i == $len -1) {
            $schema .= '';
        }else{
            $schema .= ', ';
        }
        $i++;
        }

        $schema .=  ']';

        $schema .= '}</script>';

        return $schema;
    }


    public static function SchemaNewsArticle(){
        $postSLUG = 'professionally-transition-alternative-scenarios';
        $post = Posts::where('slug',$postSLUG)->first();
        $author = DB::table('users')->where('id',$post->author)->first()->name;
        $meta = DB::table('post_meta')->where('post_id',$post->id)->get();
        $meta = PublicHelper::readMetas($meta);
        $linkToPost = route('single_gallery',$postSLUG);
        $title = $post->title;
        $thumbnail = ($post->featured_image != '' ? $post->featured_image : asset('images/imageNotFound.jpg'));
        $published_at = $post->published_date;
        $updated_at = $post->updated_at ?? $post->published_date;
        $writerName = $author ?? 'Admin';
        $logo = asset('images/sbt-icon.png');
        $metaDesc = $meta->meta_desc ?? $title;


        if($post->post_type == 'video'){
            $type = 'VideoObject';
        }else{
            $type = 'NewsArticle';
        }   

        $schema = ' <script type="application/ld+json">
                    {
                        "@context": "http://schema.org",
                        "@type": "'.$type.'",';

        if($post->post_type == 'video'){
        $schema .=      '   "@id": "'.$linkToPost.'",
                            "name" : "'.$title.'",
                            "thumbnailUrl" : "'.$thumbnail.'",
                            "uploadDate" : "'.$published_at.'",
                            "duration" : "'.$meta->video_url.'",';
        }else{
        $schema .=      '"mainEntityOfPage": {
                            "@type": "WebPage",
                            "@id": "'.$linkToPost.'#"
                        },
                        "image": {
                            "@type": "ImageObject",
                            "url": "'.$thumbnail.'",
                            "height": 500,
                            "width": 750
                        },';
        }
        $schema .=      '"headline": "'.$title.'",
                        "datePublished": "'.$published_at.'",
                        "dateModified": "'.$updated_at.'",
                        "author": {
                            "@type": "Person",
                            "name": "'.$writerName.'"
                        },
                        "publisher": {
                            "@type": "Organization",
                            "name": "Mnews.co.id",
                            "logo": {
                                "@type": "ImageObject",
                                "url": "'.$logo.'",
                                "width": 255,
                                "height": 55
                            }
                        },
                        "description": "'.$metaDesc.'"
                    }
                    </script>';
        return $schema; 
    }

    /**
     * Return all selected Schema
     */
    public static function printSchema($page,$kategori = '',$postSLUG = ''){
        $send = '';
        switch ($page) {
            case 'Home':
                $send .= PublicHelper::SchemaWebSite();
                $send .= PublicHelper::SchemaOrganization();
                break;
            case 'singleGaleri':
                $send .= PublicHelper::SchemaWebSite();
                $send .= PublicHelper::SchemaOrganization();
                $send .= PublicHelper::SchemaNewsArticle();
                break;
            default:
                $send .= PublicHelper::SchemaWebSite();
                $send .= PublicHelper::SchemaOrganization();
                break;
        }
        return $send;
    }

    public static function getDuration($video_url) {

        $videoID = explode('embed/', $video_url)[1];
        $api = 'AIzaSyBov_4dHsjC6quuMJch0Xlw_2au3tCEqJo';

        $gdata_uri = "https://www.googleapis.com/youtube/v3/videos?id=$videoID&key=$api&part=contentDetails";
        $json = file_get_contents($gdata_uri);
        $obj = json_decode($json);
        $duration = $obj->items[0]->contentDetails->duration;
        return $duration;
    }

}