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
use Carbon\Carbon;
use Mail;
use View;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{

	function __construct(Request $request)
	{
		Carbon::setLocale('Indonesia');
		$var['page'] = 'Sahabat UMKM Indonesia';

		$analytic = Option::where('key', 'analytic')->first()->value ?? '';
        $fb_pixel = Option::where('key', 'fb_pixel')->first()->value ?? '';
        $link_fb = Option::where('key', 'link_fb')->first()->value ?? '';
        $link_tw = Option::where('key', 'link_tw')->first()->value ?? '';
        $link_ig = Option::where('key', 'link_ig')->first()->value ?? '';
        $link_yt = Option::where('key', 'link_yt')->first()->value ?? '';

        View::share('var', $var);
        View::share('analytic', $analytic);
        View::share('fb_pixel', $fb_pixel);
        View::share('link_fb', $link_fb);
        View::share('link_ig', $link_ig);
        View::share('link_tw', $link_tw);
        View::share('link_yt', $link_yt);
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
		$var['videos'] = DB::table('post_view')->where('post_type','video')->orderBy('published_date','desc')->paginate(4);
		return view('page.home')->with(['var' => $var]);
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
		$user = new \App\Helpers\SSOHelper;
		$var['mentors'] =  $user->mentors()->users;
		
		return view('page.mentor')->with(['var' => $var]);
	}
	/**
     * Show single mentor page.
     * @return Response
     */
	public function mentorSingle($mentorId){
		$var['page'] = "mentorSingle";
		$user = new \App\Helpers\SSOHelper;
		$var['mentors'] =  $user->mentors("$mentorId")->users;
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
     * Show single video page.
     * @return Response
     */
	public function singleVideo($slug){
		$var['page'] = "singleVideo";
		$var['video'] = DB::table('post_view')->where('slug',$slug)->first();
		$postMetas = DB::table('post_meta')->where('post_id',$var['video']->id)->get();
		$postMetas = $this->readMetas($postMetas);
		$var['tags'] = PostHelper::get_post_tag($var['video']->id);
		$var['categories'] = PostHelper::get_post_category($var['video']->id);
		$var['videoEmbed'] = $postMetas->video_url ?? [];

		$nextVideo = DB::table('post_view')->where('post_type','video')->orderBy('published_date','desc')->where('published_date','>',$var['video']->published_date)->limit(1)->get();
		$prevVideo = DB::table('post_view')->where('post_type','video')->orderBy('published_date','desc')->where('published_date','<',$var['video']->published_date)->limit(1)->get();
		$var['nextVid'] = $nextVideo[0]->slug ?? '';
		$var['prevVid'] = $prevVideo[0]->slug ?? '';

		$post_ids = DB::table('post_view')->where('post_type', 'video')->select('id')->get();
		// dd($post_ids[0]->id);
        $data = [];

        foreach ($post_ids as $post) {
            $post_metas = PostMeta::where('post_id',$post->id)->get();

            $metas = new \stdClass;;
            foreach ($post_metas as $key => $value) {
                $metas->{$value->key} = $value->value;
            }
            dd($post->id);
        }

		return view('page.singleVideo')->with(['var' => $var]);
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
	public function video(){
		$var['page'] = "Video";
		$var['videos'] = DB::table('post_view')->where('post_type','video')->orderBy('published_date','desc')->paginate(6);
		return view('page.video')->with(['var' => $var]);
	}

	/**
     * Show video search result.
     * @return Response
     */
	public function searchVideo(Request $request){
		$query = $request->get('q');
		// dd($query);
		$var['page'] = "Video";
		$var['query'] = $query;
		$var['videos'] = DB::table('post_view')
						 ->where('post_type','video')
						 ->where('title','like','%'.$query.'%')
						 ->orderBy('published_date','desc')
						 ->paginate(6);
		return view('page.searchVideo')->with(['var' => $var]);
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
        $pesan = $req->input('pesan');

        $data = array(
            'name' => $name,
            'email_from' => $email,
            'pesan' => $pesan,
        );

        Mail::send('emails.contact', $data, function ($message) use ($data) {

            $message->from($data['email_from'], $data['name']);
            $message->to('fahmial51@gmail.com', 'info@sahabatumkm.id')->subject('Pesan dari form kontak sahabatumkm.id');

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