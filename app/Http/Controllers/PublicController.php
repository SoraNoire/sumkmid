<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use Cookie;
use App\Helpers\SSOHelper as SSO;
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

        $var['gallery'] = Option::where('key', 'gallery_section')->first()->value ?? '';
        if ($var['gallery'] != '') {
            $var['gallery'] = json_decode($var['gallery']);
        } else {
        	$var['gallery'] = [];
        	$var['gallery']['title'] = 'Galeri Sahabat UMKM';
        	$var['gallery']['category'] = '0';
            $var['gallery'] = json_encode($var['gallery']);
            $var['gallery'] = json_decode($var['gallery']);
        }

		$gallery_title = $var['gallery']->title;
		$split = explode(' ', $gallery_title);
		$split[count($split)-1] = "</span><span>".$split[count($split)-1]."</span>";
		$split[0] = "<span>".$split[0];
		$var['gallery_name'] = implode(" ", $split);

		if ($var['gallery']->category > 1) {
			$post_ids = PostHelper::get_post_archive_id('category', $var['gallery']->category);
			$var['videos'] = DB::table('post_view')
							->whereIn('post_type',['video', 'gallery'])
							->whereIn('id', $post_ids)
							->orderBy('published_date','desc')
							->limit(6)
							->get();
		} else {
			$var['videos'] = DB::table('post_view')->whereIn('post_type',['video', 'gallery'])->orderBy('published_date','desc')->limit(4)->get();
		}

		$var['mentors'] = app()->OAuth->mentors()->users;
		
		$program = Option::where('key', 'program')->first()->value ?? '';
        $var['programs'] = [];
        if ($program != '') {
            $var['programs'] = json_decode($program);
        }

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

        $var['video'] = Option::where('key', 'video_section')->first()->value ?? '';
        if ($var['video'] != '') {
            $var['video'] = json_decode($var['video']);
        }

        $var['quote'] = Option::where('key', 'quotes_section')->first()->value ?? '';
        if ($var['quote'] != '') {
            $var['quote'] = json_decode($var['quote']);
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
     * Show Tentang page.
     * @return Response
     */
	public function tentang(){
        $var['page'] = "Tentang Kami";

        $get = Posts::where('slug','tentang-kami')->where('post_type','page')->orWhere('slug','tentang')->first();

        if($get){
        	$var['data'] = $get;
        	return view('page.tentangDinamis')->with(['var' => $var]);
        }

		return view('page.tentang')->with(['var' => $var]);
	}

	/**
     * Show kontak page.
     * @return Response
     */
	public function kontak(){
        $var['page'] = "Kontak";
		return view('page.kontak')->with(['var' => $var]);
	}

	/**
     * Show mentors page.
     * @return Response
     */
	public function mentor(){
		$var['page'] = "Mentor";
		$var['mentors'] = app()->OAuth->mentors()->users;
		
		return view('page.mentor')->with(['var' => $var]);
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
			return view('page.mentorSingle')->with(['var' => $var]);
		}

		return redirect(route('public_mentor'));

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
     * Show event page.
     * @return Response
     */
	public function event(){
        $var['page'] = "Event";
        // $limit = 5;
        // $offset = $limit - $limit;
        // $next = 2;

        $var['events'] = DB::table('post_meta')
        			->where('key', '=', 'open_at')
        			->join('post_view', function ($join) {
            			$join->on('post_meta.post_id', '=', 'post_view.id')
            				 ->where('post_view.post_type','event');
        			})
    				->orderby('value', 'desc')
    				// ->offset($offset)
    				// ->limit($limit)
					// ->get();
					->paginate(3);

		return view('page.event')->with(['var' => $var]);
	}

	/**
     * Show event page.
     * @return Response
     */
	public function event_archive($page){
        $var['page'] = "Event";
        $limit = 5;
        $offset = ($page * $limit) - $limit;
        $next = $page + 1;

        $events = DB::table('post_view')->where('post_type','event')->orderby('published_date', 'desc')->offset($offset)->limit($limit)->get();
        
		return view('page.event')->with(['var' => $var, 'events' => $events, 'next' => $next]);
	}

	/**
     * Show video page.
     * @return Response
     */
	public function gallery(){
		$var['page'] = "Galeri";
		$var['posts'] = DB::table('post_view')->whereIn('post_type',['video', 'gallery'])->orderBy('published_date','desc')->paginate(6);
		return view('page.gallery')->with(['var' => $var]);
	}

	/**
     * Show single gallery page.
     * @return Response
     */
	public function singleGallery($slug){
		$var['page'] = "singleGaleri";
		$var['content'] = DB::table('post_view')->where('slug',$slug)->first();
		$postMetas = DB::table('post_meta')->where('post_id',$var['content']->id)->get();
		$postMetas = $this->readMetas($postMetas);
		$var['tags'] = PostHelper::get_post_tag($var['content']->id);
		$var['categories'] = PostHelper::get_post_category($var['content']->id);

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

	/**
     * Show gallery search result.
     * @return Response
     */
	public function searchGallery(Request $request){
		$query = $request->get('q');
		// dd($query);
		$var['page'] = "Search Galeri";
		$var['query'] = $query;
		$var['posts'] = DB::table('post_view')
						 ->whereIn('post_type',['video', 'gallery'])
						 ->where('title','like','%'.$query.'%')
						 ->orderBy('published_date','desc')
						 ->paginate(6);
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

		$var['page'] = "Category Video ".$cat->name;
		$var['archive'] = "Category : ".$cat->name;
		$post_ids = PostHelper::get_post_archive_id('category', $cat->id);
		$var['posts'] = DB::table('post_view')
						->whereIn('post_type',['video', 'gallery'])
						->whereIn('id', $post_ids)
						->orderBy('published_date','desc')
						->paginate(6);
		return view('page.gallery')->with(['var' => $var]);
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

		$var['page'] = "Tag Video ".$tag->name;
		$var['archive'] = "Tag : ".$tag->name;
		$post_ids = PostHelper::get_post_archive_id('tag', $tag->id);
		$var['posts'] = DB::table('post_view')
						->whereIn('post_type',['video', 'gallery'])
						->whereIn('id', $post_ids)
						->orderBy('published_date','desc')
						->paginate(6);
		return view('page.gallery')->with(['var' => $var]);
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

        $email_to = config('app.email_info') ?? 'info@mdirect.id';
        dd($email_to);

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
		return view('page.newsletter')->with(['var' => $var, 'email' => $email]);
	}
}