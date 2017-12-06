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
use Carbon\Carbon;
use Mail;

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
		$var['videos'] = DB::table('posts')->where('post_type','video')->where('deleted',0)->orderBy('published_date','desc')->paginate(4);
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
		$user = new \App\Helpers\SSOHelper;
		$var['mentors'] =  $user->mentors()->users;
		
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
		$var['tags'] = PostHelper::get_post_tag($var['video']->id);
		$var['categories'] = PostHelper::get_post_category($var['video']->id);
		$var['videoEmbed'] = $postMetas->video_url ?? [];

		$nextVideo = DB::table('posts')->where('post_type','video')->orderBy('published_date','desc')->where('published_date','>',$var['video']->published_date)->limit(1)->get();
		$prevVideo = DB::table('posts')->where('post_type','video')->orderBy('published_date','desc')->where('published_date','<',$var['video']->published_date)->limit(1)->get();
		$var['nextVid'] = $nextVideo[0]->slug ?? '';
		$var['prevVid'] = $prevVideo[0]->slug ?? '';
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
        			->join('posts', function ($join) {
            			$join->on('post_meta.post_id', '=', 'posts.id')
            				 ->where('posts.post_type','event')
		    				 ->where('posts.deleted', 0)
		    				 ->where('posts.status', 1);
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

        $events = Posts::where('post_type','event')->orderby('published_date', 'desc')->offset($offset)->limit($limit)->get();
        
		return view('page.event')->with(['var' => $var, 'events' => $events, 'next' => $next]);
	}

	/**
     * Show video page.
     * @return Response
     */
	public function video(){
		$var['page'] = "Video";
		$var['videos'] = DB::table('posts')->where('post_type','video')->where('deleted',0)->orderBy('published_date','desc')->paginate(3);
		return view('page.video')->with(['var' => $var]);
	}
	public function searchVideo(request $request){
		$query = $request->input('q');
		$var['page'] = "searchVideo";
		$var['query'] = $query;
		$var['videos'] = DB::table('posts')->where('post_type','video')->where('title','like','%'.$query.'%')->orderBy('published_date','desc')->get();
		return view('page.video')->with(['var' => $var]);
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
}