<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use Cookie;
use App\Helpers\SSOHelper as SSO;

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
	    return response('cannot login');

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
        return redirect("http://authdev.mdirect.id/client_login?appid=$appid&next=$next")->send();

	}

}