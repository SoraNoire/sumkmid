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
use Modules\Blog\Entities\PostMeta;
use Modules\Video\Entities\Video;
use Carbon\Carbon;

class PublicController extends Controller
{

	function __construct(Request $request)
	{
		Carbon::setLocale('Indonesia');
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
     * Show mentor page.
     * @return Response
     */
	public function mentor(){
        $var['page'] = "Mentor";
		return view('page.mentor')->with(['var' => $var]);
	}

	/**
     * Show User Setting page.
     * @return Response
     */
	public function userSetting(){
        $var['page'] = "userSetting";
		return view('page.userSetting')->with(['var' => $var]);
	}

	public function singleVideo($slug){
		$var['page'] = "singleVideo";
		$var['video'] = DB::table('posts')->where('slug',$slug)->first();
		$postMetas = DB::table('post_meta')->where('post_id',$var['video']->id)->get();
		$postMetas = $this->readMetas($postMetas);
		

		$var['tags'] = $postMetas->tags ?? '';
		$var['categories'] = $postMetas->categories ?? '';

		return view('page.singleVideo')->with(['var' => $var]);
	}

	/**
     * Show event page.
     * @return Response
     */
	public function event(){
        $var['page'] = "Event";
        $limit = 5;
        $offset = $limit - $limit;
        $next = 2;

        $events = Posts::where('post_type','event')->where('deleted', 0)->where('status', 1)->orderby('published_date', 'desc')->offset($offset)->limit($limit)->get();

        $newdata = array();
        foreach ($events as $data) {
            $post_metas = PostMeta::where('post_id',$data->id)->get();
            $post_metas = $this->readMetas($post_metas);

            $data->event_type	= $post_metas->event_type ?? '';
            $data->event_url    = $post_metas->event_url ?? '';
            $data->gmaps_url	= $post_metas->gmaps_url ?? '';
            $data->location     = $post_metas->location ?? '';
            $data->htm          = $post_metas->htm ?? '';
            $data->open_at      = $post_metas->open_at ?? '';
            $data->closed_at    = $post_metas->closed_at ?? '';
            $data->meta_desc    = $post_metas->meta_desc ?? '';
            $data->meta_title   = $post_metas->meta_title ?? '';
            $data->meta_keyword = $post_metas->meta_keyword ?? '';
            $data->mentors      = json_decode($post_metas->event_mentor ?? '') ?? [];

            $newdata[] = $data;
        }
        $events = $newdata;
        // foreach ($events[0]->mentors as $key) {
        // 	var_dump($key);
        // }
        // die();
        // dd($events[0]->mentors);

		return view('page.event')->with(['var' => $var, 'events' => $events, 'next' => $next]);
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

        $events = Posts::where('post_type','event')->orderby('published_date', 'desc')->offset($offset)->limit($limit)->get();
        
		return view('page.event')->with(['var' => $var, 'events' => $events, 'next' => $next]);
	}

	/**
     * Show video page.
     * @return Response
     */
	public function video(){
		$var['page'] = "Video";
		$var['videos'] = DB::table('posts')->where('post_type','video')->orderBy('published_date','desc')->get();
		return view('page.video')->with(['var' => $var]);
	}

	function readMetas($arr=[]){
        $metas = new \stdClass;;
        foreach ($arr as $key => $value) {
            $metas->{$value->key} = $value->value;
        }
        return $metas;
    }
}