<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use Cookie;
use App\Helpers\SSOHelper as SSO;
use App\Events;

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
				return redirect('/')->send();
			}
			Cookie::queue(config('auth.ssocookie'), "", -10080);
			return redirect(route('ssologin'));
		}
	    return response('cannot login <a href="/">home</a>');

	}

	public function ssoLogin(Request $request)
	{
		if (Cookie::get( config('auth.ssocookie') ))
		{
			// test token 
			if (app()->SSO->auth()){
				return redirect('/')->send();
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
     * Show event page.
     * @return Response
     */
	public function event(){
        $var['page'] = "Event";
        $events = Events::get();
		return view('page.event')->with(['var' => $var, 'events' => $events]);
	}

	/**
     * Show video page.
     * @return Response
     */
	public function video(){
        $var['page'] = "Video";
		return view('page.video')->with(['var' => $var]);
	}

}