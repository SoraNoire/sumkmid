<?php

namespace Rabbit\OAuthClient\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rabbit\OAuthClient\Utils\OAuth;
use Rabbit\OAuthClient\Models\Users;
use Session;
use Cookie;

class PController extends Controller
{
    /**
         * redirect to OAuth server
         *
         * @return void
         * @author 
         **/
        public function OAuthLogin()
        {
        	// dd(session('logid'));
        	// dd(app()->OAuth->Auth());
            if ( session('logid') )
            {
            	// dd(session('logid'));
                // test token 
                if( app()->OAuth->Auth() && in_array(app()->OAuth->Auth()->role, ['admin','editor','writer']))
                    {
                        return redirect(route('panel.dashboard'))->send();
                    }
                Session::pull('logid');
                // Cookie::queue(config('auth.ssocookie'), "", -10080);
                return redirect('/');
            }
            $next = urlencode(url()->previous()) ?? '/';
            $appid = config('auth.md_sso.APP_ID');
            return redirect(config('auth.md_sso.APP_LOGIN') . "?appid=$appid&next=$next")->send();
        }    

        /**
         * redirect to OAuth server
         *
         * @return void
         * @author 
         **/
        public function OAuthRegister()
        {
            $appid = config('auth.md_sso.APP_ID');
            return redirect(config('auth.md_sso.APP_REGISTER') . "?appid=$appid")->send();
        } 


        /**
         * OAuth logout
         *
         * @return void
         * @author 
         **/
        public function OAuthlogout()
        {

            // dd($request->input('withmail'));
            app()->OAuth->logout();
            Session::pull('logid');
            return redirect('/');

        }


        /**
         * OAuth callback login
         *
         * @return void
         * @author 
         **/
        public function OAuthCallback(Request $request)
        {

            // dd($request->input('withmail'));

            $token = $request->input('token');
            $next = (strpos(urldecode($request->input('next')), '/login') !== false) ? '/' : urldecode($request->input('next'));

            session(['temptoken'=>$token]);
            session(['next'=>$next]);

            if(true == $request->input('withmail'))
            {
                return view('oa::backend.challenge',['token'=>$token]);
            }

            return self::doOauthLogin();

        }

        public function emailChallenge(Request $request)
        {
        	$code = $request->input('code');
        	return self::doOauthLogin($code);
        }

        public function resendEmailChallenge(Request $request)
        {
            $token = $request->input('token');
            if ($token){
                $code = \Rabbit\OAuthClient\Utils\OAuth::DirectCurl('/resend_code',
                                        ['token'=>$token],
                                'post');
                if($code)
                {
                    return response($code->message);
                }
            }
            return response('Error');
        }

        private static function doOauthLogin($code=false)
        {
        	if($code)
        	{
        		$tokenRequest = \Rabbit\OAuthClient\Utils\OAuth::DirectCurl('/token_request',
        								[
        									'token'=>session('temptoken'),
        									'mail_code'=> $code
        								],
        						'post');
        	}
        	else
        	{

        		$tokenRequest = \Rabbit\OAuthClient\Utils\OAuth::DirectCurl('/token_request',
        								['token'=>session('temptoken')],
        						'post');	
        	}

        	if(isset($tokenRequest->code))
        	{
        		if ( 55 ==$tokenRequest->code )
        		{
        			return view('oa::backend.challenge',['error'=>$tokenRequest->message]);
        		}
        	}

            if ($tokenRequest && $tokenRequest->success )
            {
                // give cookie for 7 days


                $user = app()->OAuth->Auth($tokenRequest->data->token);
                if($user->success && $user->data)
                {
                	$user = $user->data;

                	$locUser = Users::where('master_id',$user->id)->first();
                	if($locUser)
                	{
                		// update current user

                		$locUser->name = $user->name;
	                	$locUser->master_id = $user->id;
	                	$locUser->username = $user->username;
	                	$locUser->avatar = $user->foto_profil??'';
	                	$locUser->role = $user->role;
	                	$locUser->token = $tokenRequest->data->token;
	                	$locUser->sessid = session()->getId();
	                	$locUser->options = '';
	                	$locUser->cookieid = '';
	                	$locUser->description = $user->description??'';
	                	$locUser->last_sync = date('Y-m-d H:i:s');
	                	$locUser->save();
                	}
                	else
                	{
	                	$locUser = new Users;
	                	$locUser->name = $user->name;
	                	$locUser->master_id = $user->id;
	                	$locUser->username = $user->username;
	                	$locUser->avatar = $user->foto_profil??'';
	                	$locUser->role = $user->role;
	                	$locUser->options = '';
	                	$locUser->cookieid = '';
	                	$locUser->token = $tokenRequest->data->token;
	                	$locUser->sessid = session()->getId();
	                	$locUser->description = $user->description??'';
	                	$locUser->last_sync = date('Y-m-d H:i:s');
	                	$locUser->save();
	                }

                }
                session(['logid'=>$locUser->id]);
                $next = Session::pull('next', url("/"));
                Session::pull('temptoken');

                return redirect($next); 

            }
            

            // has been logged in
            if ( session('logid') )
            {

                // test token 
                if (app()->OAuth->Auth()){
                    if( in_array(app()->OAuth->Auth()->role, ['admin','editor','writer']))
                    {
                        return redirect(route('panel.dashboard'))->send();
                    }
                    return redirect('/')->send();
                }

                // force logoutt
                // Session::pull('logid');
                // Cookie::queue(config('auth.ssocookie'), "", -10080);
                return redirect(route('OA.login.callback'));

            }

            // login error
            Session::pull('logid');
            // return response('cannot login <a href="/">home</a>');
            // Cookie::queue(config('auth.ssocookie'), "", -10080);
            return redirect(route('OA.login'));
        }
}
