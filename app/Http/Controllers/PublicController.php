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
use Modules\Video\Entities\Video;

class PublicController extends Controller
{

	function __construct(Request $request)
	{
		
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
        $paginate = 5;
        $offset = $paginate - $paginate;
        $next = 2;

        $events = Posts::where('post_type','event')->orderby('published_date', 'desc')->offset($offset)->limit($paginate)->get();

        $max_post = count($events);
        $max_per_page = $paginate;
        $max_page = ceil($max_post/$paginate);
		return view('page.event')->with(['var' => $var, 'events' => $events, 'next' => $next]);
	}

	/**
     * Show event page.
     * @return Response
     */
	public function event_archive($page){
        $var['page'] = "Event";
        $paginate = 5;
        $offset = ($page * $paginate) - $paginate;
        $next = $page + 1;

        $events = Events::orderby('published_at', 'desc')->offset($offset)->limit($paginate)->get();
        // dd($events);
        $max_post = count($events);
        $max_per_page = $paginate * $page;
        $max_page = ceil($max_post/$paginate);
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