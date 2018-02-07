<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use Cookie;
use App\Helpers\SSOHelper as SSO;
use App\Helpers\PublicHelper;
use App\Events;
use DB;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Blog\Entities\PostMeta;
use Modules\Video\Entities\Video;
use Modules\Blog\Entities\Option;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Tags;
use Modules\Blog\Entities\Slider;
use Carbon\Carbon;
use Mail;
use View;
use Modules\Blog\Entities\Media;
use Illuminate\Support\Facades\Validator;
use Vinkla\Instagram\Instagram;
use Response;

class PublicController extends Controller
{

	function __construct(Request $request)
	{
	
		$this->middleware('shbuser');

		Carbon::setLocale('Indonesia');
		$var['page'] = 'Sahabat UMKM Indonesia';


		$analytic = Option::where('key', 'analytic')->first()->value ?? '';
		$tagmanager = Option::where('key', 'tag_manager')->value ?? '';
        $fb_pixel = Option::where('key', 'fb_pixel')->first()->value ?? '';
        $link_fb = Option::where('key', 'link_fb')->first()->value ?? '';
        $link_tw = Option::where('key', 'link_tw')->first()->value ?? '';
        $link_ig = Option::where('key', 'link_ig')->first()->value ?? '';
        $link_yt = Option::where('key', 'link_yt')->first()->value ?? '';
        $link_in = Option::where('key', 'link_in')->first()->value ?? '';
        $link_gplus = Option::where('key', 'link_gplus')->first()->value ?? '';
        $footer_desc = Option::where('key', 'footer_desc')->first()->value ?? '';
        $email_info = Option::where('key', 'email')->first()->value ?? config('app.email_info');

        View::share('var', $var);
        View::share('analytic', $analytic);
        View::share('tagmanager', $tagmanager);
        View::share('fb_pixel', $fb_pixel);
        View::share('link_fb', $link_fb);
        View::share('link_ig', $link_ig);
        View::share('link_tw', $link_tw);
        View::share('link_yt', $link_yt);
        View::share('link_gplus', $link_gplus);
        View::share('link_in', $link_in);
        View::share('footer_desc', $footer_desc);
        View::share('email_info', $email_info);
	}

	public function login(Request $request)
	{
		$token = $request->input('token');
		$tokenRequest = SSO::SSOCurl('/token_request',['token'=>$token],'post');
		$next = urldecode($request->input('next'));
		
		if ($tokenRequest && $tokenRequest->success )
		{
			// give cookie for 7 days
	        Cookie::queue(config('auth.ssocookie'), $tokenRequest->data->token, 10080);
	        // setcookie(config('auth.ssocookie'), $tokenRequest->data->token, 10080);
	        // return (Cookie::get( config('auth.ssocookie')) ? 'masuk' : 'tidak masuk');
	        return redirect($next);	
	    }
	    if (Cookie::get( config('auth.ssocookie') ))
		{
			// test token 
			if (app()->SSO->auth()){
				return redirect('/admin/blog')->send();
			}
			Cookie::queue(config('auth.ssocookie'), "", -10080);
			return redirect(route('ssologin'));
		}
	    // return response('cannot login <a href="/">home</a>');
	    Cookie::queue(config('auth.ssocookie'), "", -10080);
		return redirect(route('ssologin'));

	}

	public function ssoLogin(Request $request)
	{
		if (Cookie::get( config('auth.ssocookie') ))
		{
			// test token 
			if (app()->SSO->auth()){
				return redirect('/admin/blog')->send();
			}
			Cookie::queue(config('auth.ssocookie'), "", -10080);
			return redirect(route('ssologin'));
		}
		$next = urlencode(url()->previous()) ?? '/';
		$appid = env('MD_APP_ID');
		return redirect(config('auth.md_sso.APP_LOGIN') . "?appid=$appid&next=$next")->send();
	}

	/**
     * Show homepage.
     * @return Response
     */
	public function home(){
        $var['page'] = "Home";

        $sliders = Slider::get();
        $n=1;
        $var['sliders'] = [];
        foreach ($sliders as $slider) {
            if($n % 2 == 0){
                $slider->position = 'left';
            }else{
                $slider->position = 'right';
            }
            $var['sliders'][] = $slider;
            $n++;
        }

        $about = Option::where('key', 'about_us')->first()->value ?? '';
        if ($about != '') {
            $about = json_decode($about);
        } else {
        	$about = new \stdClass;;
        	$about->title = 'Tentang Kami';
        	$about->text = '';
        }
        $about->title = PublicHelper::print_section_title($about->title);
		$var['about_us'] = $about;

		$program = Option::where('key', 'list_program')->first()->value ?? '';
        $var['programs'] = [];
        if ($program != '') {
            $var['programs'] = json_decode($program);
        } 

        $program_section = Option::where('key', 'program_section')->first()->value ?? '';
        if ($program_section) {
            $var['program'] = json_decode($program_section);
        } else {
        	$program_section = new \stdClass;;
        	$program_section->title = 'Program Sahabat UMKM';
            $program_section->desc = '';
            $program_section->button = '';
            $program_section->url = '';
            $var['program'] = $program_section;
        }
        $var['program']->title = PublicHelper::print_section_title($var['program']->title);

        $var['post_section'] = Option::where('key', 'post_section')->first()->value ?? '';
        $post = Option::where('key', 'post_section')->first()->value ?? '';
        if ($post != '') {
            $post = json_decode($post);
        } else {
        	$post = new \stdClass;;
        	$post->title = 'Galeri Sahabat UMKM';
            $post->use_gallery = 1;
            $post->category = 0;
        }
		
		$post->title = PublicHelper::print_section_title($post->title);

       	$post->data = PublicHelper::getMNewsPosts();
		if ($post->use_gallery == 1) {
			if ($post->category > 1) {
				$post_ids = PostHelper::get_post_archive_id('category', $post->category);
				$post->data = DB::table('post_view')
								->whereIn('post_type',['video', 'gallery'])
								->whereIn('id', $post_ids)
								->orderBy('published_date','desc')
								->limit(8)
								->get();
			} else {
				$post->data = DB::table('post_view')
								->whereIn('post_type',['video', 'gallery'])
								->orderBy('published_date','desc')
								->limit(8)
								->get();
			}

			foreach ($post->data as $key => $value) {
				$postMetas = DB::table('post_meta')->where('post_id',$value->id)->get();
				$postMetas = $this->readMetas($postMetas);
				$value->post_desc = $postMetas->meta_desc ?? '';
				$value->link = url('/galeri/'.$value->slug); 
				$value->featured_img = $value->featured_image;
				$value->date_published = $value->published_date;
				if ( $value->post_desc == '') {
					$value->post_desc = str_limit(html_entity_decode(strip_tags($value->content)), 250);
				}
			}
		}
		$var['post'] = $post;

		$socfeed = Option::where('key', 'socfeed_section')->first()->value ?? '';
        if ($socfeed != '') {
            $socfeed = json_decode($socfeed);
        } else {
        	$socfeed = new \stdClass;;
        	$socfeed->title = 'Tumbuh dan Berkembang Bersama';
        }
        $socfeed->title = PublicHelper::print_section_title($socfeed->title);
        $var['socfeed'] = $socfeed;

        $instagram_token = Option::where('key', 'instagram_token')->first()->value ?? '';
        if ($instagram_token != '') {
        	try {	
				$instagram = new Instagram($instagram_token);
				$var['instagram'] = $instagram->get();
        	} catch (\Exception $e) {

        	}
        }

		$var['mentors'] = app()->OAuth->mentors()->users ?? [];
		$mentor = Option::where('key', 'mentor_section')->first()->value ?? '';
        if ($mentor != '') {
            $mentor = json_decode($mentor);
        } else {
        	$mentor = new \stdClass;;
        	$mentor->title = 'Our Mentor';
        }
        $mentor->title = PublicHelper::print_section_title($mentor->title);
        $var['mentor'] = $mentor;

		return view('page.home')->with(['var' => $var]);
	}

	/**
     * Show single page.
     * @return Response
     */
	public function single_page($slug){
        $page = DB::table('post_view')->where('post_type', 'page')->where('slug', $slug)->first();
        if (isset($page)) {
            $templates = PostHelper::get_page_templates_list();
        	$var['page'] = $page->title;
        	$var['slug'] = $page->slug;
        	$var['content'] = $page->content;
			$postMetas = DB::table('post_meta')->where('post_id',$page->id)->get();
			$postMetas = $this->readMetas($postMetas);

			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'article');
        	$Meta->set('meta_title', $postMetas->meta_title ?? $page->title);
        	$Meta->set('meta_desc', $postMetas->meta_desc ?? str_limit( html_entity_decode(strip_tags($page->content)), 250 ));
        	$Meta->set('meta_keyword', $postMetas->meta_keyword ?? '');
        	$Meta->set('meta_image', $page->featured_image ?? '');

			if (isset($postMetas->page_template)) {
				switch ($postMetas->page_template) {
					case 'template.mentor':
						$var['mentors'] = app()->OAuth->mentors()->users;
					break;

					case 'template.event':
					 	$var['events'] = DB::table('post_meta')
					        			->where('key', '=', 'open_at')
					        			->join('post_view', function ($join) {
					            			$join->on('post_meta.post_id', '=', 'post_view.id')
					            				 ->where('post_view.post_type','event');
					        			})
					    				->orderby('value', 'desc')
										->paginate(3);
					break;

					case 'template.kontak':
						# code...
					break;

					case 'template.tentang':
						
					break;

					case 'template.gallery':
						$var['posts'] = DB::table('post_view')
										->whereIn('post_type',['video', 'gallery'])
										->orderBy('published_date','desc')
										->paginate(6);
					break;
					
					default:
						$postMetas->page_template = 'page.singlePage';
						break;
				}
				return view($postMetas->page_template)->with(['var' => $var]);
			}
        }
		return view('errors.404');
	}

	/**
     * Show single Event page.
     * @return Response
     */
	public function eventSingle($slug){
		$get = DB::table('post_view')->where('post_type','event')->where('slug',$slug)->first();
		if($get){
			$postMetas = DB::table('post_meta')->where('post_id',$get->id)->get();
			$postMetas = $this->readMetas($postMetas);
			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'article');
        	$Meta->set('meta_title', 'Sahabat UMKM Event - '.(isset($postMetas->meta_title) ? $postMetas->meta_title : $get->title ));
        	$Meta->set('meta_desc', (isset($postMetas->meta_desc) ? $postMetas->meta_desc :  str_limit( html_entity_decode(strip_tags($get->content)), 250 )));
        	$Meta->set('meta_keyword', (isset($postMetas->meta_keyword) ? $postMetas->meta_keyword : ''));
        	$Meta->set('meta_image', (isset($get->featured_image) ? $get->featured_image : ''));

			$var['page'] = "eventSingle";
			$var['content'] = $get;
			$var['meta'] = $postMetas;
			// dd($postMetas->htm);


			return view('page.singleEvent')->with(['var' => $var]);	
		}else{
			return redirect(route('public_home'));
		}

		
	}

	/**
     * Show single mentor page.
     * @return Response
     */
	public function mentorSingle($username){
		$var['page'] = "mentorSingle";
		$var['mentors'] =  app()->OAuth->mentor("$username");
		if(isset($var['mentors']) && isset($var['mentors']->username)){
			$var['mentors'] = $var['mentors'];
			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'profile');
        	$Meta->set('meta_title', 'Mentor '.$var['mentors']->name ?? '');
        	$Meta->set('meta_desc', 'Mentor '.$var['mentors']->name.' - '.$var['mentors']->description ?? '');
        	$Meta->set('meta_image', $var['mentors']->avatar ?? '');

			return view('page.mentorSingle')->with(['var' => $var]);
		}

		return view('errors.404');

	}

	/**
     * Show single mentoring.
     * @return Response
     */
	public function single_mentoring($mentoring_id){
		$var['page'] = "Materi Mentoring";
		$mentoring = DB::table('post_view')->find($mentoring_id);
		if($mentoring){

			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'article');
        	$Meta->set('meta_title', 'Materi Mentoring '.$mentoring->title);
        	$Meta->set('meta_desc', str_limit(html_entity_decode(strip_tags($mentoring->content)), 200) ?? '');

        	$event_id = PostMeta::where('key', 'mentoring')->where('value', $mentoring_id)->first();
        	if (isset($event_id)) {
        		$event = DB::table('post_view')->find($event_id);

				$postMetas = DB::table('post_meta')->where('post_id',$mentoring_id)->get();
				$postMeta = $this->readMetas($postMetas);

            	$files = json_decode($postMeta->files);

				return view('page.singleMentoring')->with([ 'mentoring' => $mentoring,
										 					'event' => $event, 
										 					'postMeta' => $postMeta,
										 					'files' => $files
										 				]);
        	}
		}

		return view('errors.404');

	}

	

	private function _validate($data=[],$validator=[])
	{
		$message = [
			'required' => ':attribute dibutuhkan',
			'min' => ':attr minimal :min'
		];
		$validate = Validator::make($data,$validator,$message);
		if ($validate->fails()){
			foreach($validate->errors()->getMessages() as $k => $v)
			{
				return $v[0];
			}
		}
		return false;
	}

	/**
     * Show single gallery page.
     * @return Response
     */
	public function singleGallery($slug){
		$var['page'] = "singleGaleri";
		$var['content'] = DB::table('post_view')->where('slug',$slug)->first();
		if (isset($var['content'])) {
			$postMetas = DB::table('post_meta')->where('post_id',$var['content']->id)->get();
			$postMetas = $this->readMetas($postMetas);
			$var['tags'] = PostHelper::get_post_tag($var['content']->id);
			$var['categories'] = PostHelper::get_post_category($var['content']->id);

			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'article');
        	$Meta->set('meta_title', $postMetas->meta_title ?? $var['content']->title);
        	$Meta->set('meta_desc', $postMetas->meta_desc ?? str_limit( html_entity_decode(strip_tags($var['content']->content)), 250 ));
        	$Meta->set('meta_keyword', $postMetas->meta_keyword ?? '');
        	$Meta->set('meta_image', $var['content']->featured_image ?? '');

			if($var['content']->post_type == 'video'){
				$var['videoEmbed'] = $postMetas->video_url ?? '';
			}else{
				$gallery_images = json_decode($postMetas->gallery_images ?? '') ?? []; 
				$var['photos'] = Media::whereIn('id', $gallery_images)->get();
			}

			$nextItem = DB::table('post_view')
							->whereIn('post_type',['video', 'gallery'])
							->orderBy('published_date','desc')
							->where('published_date','>',$var['content']->published_date)
							->limit(1)
							->get();
			$prevItem = DB::table('post_view')
							->whereIn('post_type',['video', 'gallery'])
							->orderBy('published_date','desc')
							->where('published_date','<',$var['content']->published_date)
							->limit(1)
							->get();

			$var['nextItem'] = $nextItem[0]->slug ?? '';
			$var['prevItem'] = $prevItem[0]->slug ?? '';
	        $var['allcategories'] = PostHelper::get_all_categories(['video', 'gallery']);

			return view('page.singleGallery')->with(['var' => $var]);
		}
		return view('errors.404');
	}

	/**
     * Show gallery search result.
     * @return Response
     */
	public function searchGallery(Request $request){
		$query = $request->get('q');
		// dd($query);
		$var['page'] = "Cari Galeri: ".$query;
		$var['query'] = $query;
		$var['posts'] = DB::table('post_view')
						 ->whereIn('post_type',['video', 'gallery'])
						 ->where('title','like','%'.$query.'%')
						 ->orderBy('published_date','desc')
						 ->paginate(6);

		$Meta = app()->Meta;
    	$Meta->set(['meta_title', 'meta_desc', 'meta_keyword'], $var['page']);
			 
		return view('page.searchGallery')->with(['var' => $var]);
	}

	/**
     * Show gallery category archive.
     * @return Response
     */
	public function galleryCatArchive($slug){
		$cat = Categories::where('slug', $slug)->first();
		if (!isset($cat)) {
        	return view('errors.404');
        }

		$var['page'] = "Kategori Galeri ".$cat->name;
		$var['archive'] = "Kategori : ".$cat->name;
		$post_ids = PostHelper::get_post_archive_id('category', $cat->id);
		$var['posts'] = DB::table('post_view')
						->whereIn('post_type',['video', 'gallery'])
						->whereIn('id', $post_ids)
						->orderBy('published_date','desc')
						->paginate(6);

		$Meta = app()->Meta;
    	$Meta->set(['meta_title', 'meta_desc', 'meta_keyword'], $var['page']);

		return view('template.gallery')->with(['var' => $var]);
	}

	/**
     * Show gallery tag archive.
     * @return Response
     */
	public function galleryTagArchive($slug){
        $tag = Tags::where('slug', $slug)->first();
        if (!isset($tag)) {
        	return view('errors.404');
        }

		$var['page'] = "Tag Galeri ".$tag->name;
		$var['archive'] = "Tag : ".$tag->name;
		$post_ids = PostHelper::get_post_archive_id('tag', $tag->id);
		$var['posts'] = DB::table('post_view')
						->whereIn('post_type',['video', 'gallery'])
						->whereIn('id', $post_ids)
						->orderBy('published_date','desc')
						->paginate(6);

		$Meta = app()->Meta;
    	$Meta->set(['meta_title', 'meta_desc', 'meta_keyword'], $var['page']);

		return view('template.gallery')->with(['var' => $var]);
	}

	function readMetas($arr=[]){
        $metas = new \stdClass;;
        foreach ($arr as $key => $value) {
            $metas->{$value->key} = $value->value;
        }
        return $metas;
    }

    
    /**
     * Send email to contact service.
     * @param  $req
     * @return Response
     */
    public function messages_store_act(Request $req){
        $name = $req->input('nama');
        $email = $req->input('email');
        $nama_usaha = $req->input('nama_usaha');
        $contact = $req->input('telp');
        $subject = $req->input('subject');
        $pesan = $req->input('pesan');

        $email_to = app()->Meta->get('email_info');

        $data = array(
            'name' => $name,
            'email_from' => $email,
            'nama_usaha' => $nama_usaha,
            'contact' => $contact,
            'subject' => $subject,
            'pesan' => $pesan,
            'email_to' => $email_to
        );

        Mail::send('emails.contact', $data, function ($message) use ($data) {

            $message->from($data['email_from'], $data['name']);
            $message->to($data['email_to'])->subject('Pesan dari form kontak sahabatumkm.id');

        });

        return redirect('kontak')->with("msg","Terimakasih sudah menghubungi kami. Pesan yang anda kirimkan akan di baca langsung oleh departement yang bersangkutan. Kami akan hubungi anda melalui Email atau Telpon. Terimakasih ");

    }

    /**
     * Show newsletter form.
     * @return Response
     */
	public function newsletter(Request $request){
        $var['page'] = "Newsletter";
        $email = $request->get('email') ?? '';

		$Meta = app()->Meta;
    	$Meta->set(['meta_title', 'meta_desc', 'meta_keyword'], $var['page']);

		return view('page.newsletter')->with(['var' => $var, 'email' => $email]);
	}

	/**
     * Show post.
     * @return Response
     */
	public function read_post($category, $slug){
        $mnews_url = config('app.mnews_url') ?? 'http://news.mdirect.id';
        $post_url = $mnews_url.'/read/'.$category.'/'.$slug;

		return redirect($post_url);
	}

	/**
     * show gallery-sitemap.xml.
     * @return Response
     */
	public function gallery_sitemap(){
		if (PostHelper::check_cache('gallery-sitemap.xml')) {
			$response = Response::make(PostHelper::read_cache('gallery-sitemap.xml'), 200);
	        $response->header('Content-type', 'text/xml');
            return $response;
        } else {
         	$posts = DB::table('post_view')
        			->whereIn('post_type', ['gallery', 'video'])
        			->orderBy('published_date', 'desc')
        			->limit(1000)
        			->get();
	        $output = '';
	        foreach ($posts as $post) {
				$postMetas = DB::table('post_meta')->where('post_id',$post->id)->get();
				$postMeta = $this->readMetas($postMetas);
				$categories = PostHelper::get_post_category($post->id, 'name');
				$tags = PostHelper::get_post_tag($post->id, 'name');
				$categories = implode(',', $categories);
				$tags = implode(',', $tags);
				if ($post->post_type == 'gallery') {

					$gallery_images = json_decode($postMeta->gallery_images ?? '') ?? []; 
					$images = Media::whereIn('id', $gallery_images)->get();

					$output .= '<url>';
					$output .= '<loc>'.url('/galeri/'.$post->slug).'</loc>';
					foreach ($images as $key => $image) {
						$output .= '<image:image>';
						$output .= '<image:loc>'.PostHelper::getLinkimage($image->name, 'media', 'large').'</image:loc>';
						$output .= '</image:image>';
					}
					$output .= '</url> ';
				} else {
					$desc = $postMeta->meta_desc ?? str_limit(html_entity_decode(strip_tags($post->content)), 100);
					$video_url = $postMeta->video_url ?? '';

					$output .= '<url>';
					$output .= '<loc>'.url('/galeri/'.$post->slug).'</loc>';
					$output .= '<video:video>';
					$output .= '<video:thumbnail_loc>'.$post->featured_image.'</video:thumbnail_loc>';
					$output .= '<video:title>'.$post->title.'</video:title>';
					$output .= '<video:description>'.$desc.'</video:description>';
					$output .= '<video:content_loc>'.$video_url.'</video:content_loc>';
					$output .= '<video:player_loc autoplay="ap=0">'.$video_url.'</video:player_loc>';
					$output .= '<video:publication_date>'.date('c',strtotime($post->published_date)).'</video:publication_date>';
					$output .= '<video:family_friendly>yes</video:family_friendly>';
					$output .= '<video:tag>'.$tags.'</video:tag>';
					$output .= '<video:category>'.$categories.'</video:category>';
					$output .= '<video:gallery_loc title="Cooking Videos">'.url('/galeri').'</video:gallery_loc>';
					$output .= '<video:live>no</video:live>';
					$output .= '</video:video></url>';
				}
	        }
	        $content = "<?xml version=\"1.0\"?>   
	            <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" 
	        			xmlns:video=\"http://www.google.com/schemas/sitemap-video/1.1\"
	            		xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">
	              $output
	            </urlset>";


            PostHelper::create_cache($content, 'gallery-sitemap.xml');

	        $response = Response::make(PostHelper::read_cache('gallery-sitemap.xml'), 200);
	        $response->header('Content-type', 'text/xml');
            return $response;
        }
	}

	/**
     * show event-sitemap.xml.
     * @return Response
     */
	public function event_sitemap(){
		if (PostHelper::check_cache('event-sitemap.xml')) {
			$response = Response::make(PostHelper::read_cache('event-sitemap.xml'), 200);
	        $response->header('Content-type', 'text/xml');
            return $response;
        } else {
         	$posts = DB::table('post_view')
        			->where('post_type', 'event')
        			->orderBy('published_date', 'desc')
        			->limit(1000)
        			->get();
	        $output = '';
	        foreach ($posts as $post) {
				$postMetas = DB::table('post_meta')->where('post_id',$post->id)->get();
				$postMeta = $this->readMetas($postMetas);
				$meta_keywords = $postMeta->meta_keyword ?? '';

				$output .= '<url>';
				$output .= '<loc>'.url('event/'.$post->slug).'</loc>';
				$output .= '<news:news><news:publication><news:name>';
				$output .= '<![CDATA[ Event Sahabat UMKM ]]>';
				$output .= '</news:name>';
				$output .= '<news:language>id</news:language>';
				$output .= '</news:publication>';
				$output .= '<news:publication_date>'.date('c',strtotime($post->published_date)).'</news:publication_date>';
				$output .= '<news:title>';
				$output .= '<![CDATA[ '.$post->title.' ]]>';
				$output .= '</news:title>';
				$output .= '<news:keywords>';
				$output .= '<![CDATA[ '.$meta_keywords.' ]]>';
				$output .= '</news:keywords>';
				$output .= '</news:news>';
				$output .= '<image:image>';
				$output .= '<image:loc>'.$post->featured_image.'</image:loc>';
				$output .= '<image:caption>';
				$output .= '<![CDATA[ '.$post->title.' ]]>';
				$output .= '</image:caption>';
				$output .= '</image:image>';
				$output .= '</url>';

	        }
	        $content = "<?xml version=\"1.0\"?>   
	             <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:news=\"http://www.google.com/schemas/sitemap-news/0.9\" xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">
	              $output
	            </urlset>";


            PostHelper::create_cache($content, 'event-sitemap.xml');

	        $response = Response::make(PostHelper::read_cache('event-sitemap.xml'), 200);
	        $response->header('Content-type', 'text/xml');
            return $response;
        }
	}

	/**
     * show sitemap-index.xml.
     * @return Response
     */
	public function index_sitemap(){
		$this->gallery_sitemap();
		$this->event_sitemap();
		$dir = resource_path('views/template');
        $files = scandir($dir, 1);
        $templates = [];

        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
   <sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
   <sitemap>
      <loc>".url('/event-sitemap.xml')."</loc>
      <lastmod>".date('c',filemtime(app_path('Cache/event-sitemap.xml')))."</lastmod>
   </sitemap>
   <sitemap>
      <loc>".url('/gallery-sitemap.xml')."</loc>
      <lastmod>".date('c',filemtime(app_path('Cache/gallery-sitemap.xml')))."</lastmod>
   </sitemap>
   </sitemapindex>";

        $response = Response::make($content, 200);
        $response->header('Content-type', 'text/xml');
        return $response;
	}

}