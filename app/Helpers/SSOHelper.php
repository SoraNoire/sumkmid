<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Session;
use Cookie;

class SSOHelper
{
	static $appId = '';
	static $appSecret = '';
    static $appServer = '';
	static $curl = '';
    static $Auth = false;
	
	function __construct(){

        $mdconfig = config('auth.md_sso');
        self::$appId = $mdconfig['APP_ID'];
        self::$appSecret = $mdconfig['APP_SECRET'];
        self::$appServer = $mdconfig['APP_SERVER'];

        $data = [];
        $path = '/me';
        $user = self::curl($path,$data);
        if( $user->success )
        {
            $this::$Auth = $user->data;
        }
	}


    /**
     * [Auth description]
     * @return bool           [description]
     *
     */
    public function Auth()
    {
        return self::$Auth;
    }


    public static function logout()
    {
        $data = [];
        $path = '/logout';
        $action = self::curl($path,$data);
        if($action)
        {
            return $action->success;
        }
        return false;
    }

    /**
     * retrieve users by id
     *
     * @return mixed
     * @author 
     **/
    public function users($id='',$page=1)
    {

        $data = [];
        $path = "/user?page=$page&id=$id";
        $user = self::curl($path,$data);
        return null !== $user->data ? $user->data : false;

    }

    /**
     * retrieve mentors by id
     *
     * @return mixed
     * @author 
     **/
    public static function mentors($id='',$page=1)
    {

        $data = [];
        $path = "/mentor?page=$page&id=$id";
        $user = self::curl($path,$data);
        return null !== $user->data ? $user->data : false;

    }

















    /**
     * call curl
     *
     * @return void
     * @author 
     **/
    public static function SSOCurl($path='/me',$data=[],$method='get')
    {
        return self::curl($path,$data,$method);
    }


    /**
     * Run cURL
     *
     * @return void
     * @author 
     **/
    private static function curl($path='/',$data=[],$method='get')
    {
        $mdconfig = config('auth.md_sso');
        self::$appId = $mdconfig['APP_ID'];
        self::$appSecret = $mdconfig['APP_SECRET'];
        self::$appServer = $mdconfig['APP_SERVER'];

        $curl = new \anlutro\cURL\cURL;
        if ( 'post' == $method )
        {
            $request = $curl->newRequest($method,self::$appServer . $path, $data);
        }
        else
        {
            $request = $curl->newRequest($method,self::$appServer . $path);
        }

        $request->setHeader('Accept-Charset', 'utf-8');
        $request->setHeader('Accept-Language', 'en-US');
        $request->setHeader('appid', self::$appId);
        $request->setHeader('appsecret', self::$appSecret);
        $request->setHeader('clientheader', 'website');
        
        if ( null != Cookie::get( config('auth.ssocookie') ) )
        {
            $request->setHeader('usertoken',  Cookie::get( config('auth.ssocookie') ) );
        }
        
        $return = $request->send();
        if( json_decode($return) )
        {
            return json_decode($return);
        }
        
        return (object) $return;

    }

    /**
     * [Auth description]
     * @return bool           [description]
     *
     */
    public static function Auths()
    {
        $mdconfig = config('auth.md_sso');
        self::$MDKey = $mdconfig['APP_KEY'];
        self::$MDServer = $mdconfig['SERVER'];

        if( session()->exists('clientid') and session()->exists('clientsecret'))
        {
            $data = [];
            $path = '/api/userinfo';
            $user = self::curl($path,$data);
            return null !== $user->message ? $user->message : false;
        }

        return false;
    }





}