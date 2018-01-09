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

        $var['post_section'] = Option::where('key', 'post_section')->first()->value ?? '';
        $post = Option::where('key', 'post_section')->first()->value ?? '';
        if ($post != '') {
            $post = json_decode($post);
        } else {
        	$post = [];
        	$post['title'] = 'Galeri Sahabat UMKM';
            $post['use_gallery'] = 1;
            $post['category'] = 0;
            $post = json_encode($post);
            $post = json_decode($post);
        }

		$split = explode(' ', $post->title);
		$split[count($split)-1] = "</span><span>".$split[count($split)-1]."</span>";
		$split[0] = "<span>".$split[0];
		$post->title = implode(" ", $split);

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

		$var['mentors'] = app()->OAuth->mentors()->users;
		
		$program = Option::where('key', 'list_program')->first()->value ?? '';
        $var['programs'] = [];
        if ($program != '') {
            $var['programs'] = json_decode($program);
        }

        $var['program'] = Option::where('key', 'program_section')->first()->value ?? '';
        if ($var['program'] != '') {
            $var['program'] = json_decode($var['program']);
        }

		$split = explode(' ', $var['program']->title);
		$split[count($split)-1] = "</span><span>".$split[count($split)-1]."</span>";
		$split[0] = "<span>".$split[0];
		$var['program']->title = implode(" ", $split);

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

        $var['about_us'] = Option::where('key', 'about_us')->first()->value ?? '';

        $instagram_token = Option::where('key', 'instagram_token')->first()->value ?? '';
        if ($instagram_token != '') {
        	try {	
				$instagram = new Instagram($instagram_token);
				$var['instagram'] = $instagram->get();
        	} catch (\Exception $e) {

        	}
        }

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
     * Show single mentor page.
     * @return Response
     */
	public function mentorSingle($mentorId){
		$var['page'] = "mentorSingle";
		$var['mentors'] =  app()->OAuth->mentors("$mentorId")->users;

		if(isset($var['mentors'][0])){
			$var['mentors'] = $var['mentors'][0];
			$Meta = app()->Meta;
        	$Meta->set('meta_type', 'profile');
        	$Meta->set('meta_title', 'Mentor '.$var['mentors']->name ?? '');
        	$Meta->set('meta_desc', 'Mentor '.$var['mentors']->name.' - '.$var['mentors']->description ?? '');
        	$Meta->set('meta_image', $var['mentors']->foto_profil ?? '');

			return view('page.mentorSingle')->with(['var' => $var]);
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
        	$Meta->set('meta_image', $page->featured_image ?? '');

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
}