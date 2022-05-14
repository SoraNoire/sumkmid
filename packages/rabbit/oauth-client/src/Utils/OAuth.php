<?php

namespace Rabbit\OAuthClient\Utils;


use Validator;
use Illuminate\Support\Facades\Crypt;
use Rabbit\OAuthClient\Models\Users;
use Rabbit\OAuthClient\Models\Modules;
use Rabbit\OAuthClient\Models\ModulePermissions;
use Illuminate\Http\Request;
use Session;
use Cookie;
use Closure;

class OAuth
{
    static $appId = '';
    static $appSecret = '';
    static $appServer = '';
    static $curl = '';
    static $Auth = false;
    static $roles;
    static $permissions = ['read','write','edit','delete'];
    static $can = [];
    
    function __construct(){

        $mdconfig = config('auth.md_sso');
        self::$appId = $mdconfig['APP_ID'];
        self::$appSecret = $mdconfig['APP_SECRET'];
        self::$appServer = $mdconfig['APP_SERVER'];

        $data = [];
        $path = '/me';

        if( session('logid') )
        {
            $user = Users::where('id',session('logid'))->first();
            if (!$user || !isset($user->id)) {
                return redirect(route('OA.logout'));
            }
            $now = new \DateTime();
            $sy = \DateTime::createFromFormat('Y-m-d H:i:s', $user->last_sync);
            
            $sync_diff = date_diff($now,$sy)->h;
            
            // re-sync after 2 hours
            if ($sync_diff > 1) {
                $user = self::curl($path,$data);
                if ( $user && $user->success)
                {
                    $u = $user->data;
                    $local = Users::where('id',session('logid'))->first();
                    $local->last_sync = date('Y-m-d H:i:s');
                    $local->name = $u->name;
                    $local->username = $u->username;
                    $local->avatar = $u->foto_profil??'';
                    $local->description = $u->description??'';
                    $local->role = $u->role;
                    $local->save();    
                }
                else
                {
                    return redirect(route('OA.login'));
                }
                
            }
            else
            {
                $user = (object)[
                            'success' => true,
                            'data' => $user
                        ];

            }
        }
        else
        {
            $user = self::curl($path,$data);
        }

        
        if( $user->success )
        {
            $this::$Auth = $user->data;
        }

        self::$roles = explode(';', rtrim( env('OA_ROLES', 'admin;editor;writer') ,';') );
    }




    /**
     * [Auth description]
     * @return bool           [description]
     *
     */
    public static function Auth($token=false)
    {
        if ($token)
        {
            return self::curl('/me',[],'get',$token);
        }
        else
        {
            return self::$Auth;
        }
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
     * retrieve admins by id
     *
     * @return mixed
     * @author 
     **/
    public function admins($id='',$page=1)
    {

        $data = [];
        $path = "/admin?page=$page&id=$id";
        $user = self::curl($path,$data);
        return (null != $user->data) ? $user->data : false;

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
        return ( isset($user->data) && null !== $user->data) ? $user->data : false;

    }


    /**
     * retrieve single user
     *
     * @return mixed
     * @author 
     **/
    public function user($id='')
    {

        $user = $this->users($id);
        if ($user && $user->users){
            if (isset($user->users[0])){
                $user = $user->users[0];
                $user->avatar = $user->foto_profil;
                return $user;
            }
        }
        return new \stdClass;

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
        return (isset($user->data) && null !== $user->data) ? $user->data : false;

    }
    public static function mentor($username='',$page=1)
    {

        $data = [];
        $path = "/mentor?page=$page&username=$username";
        $user = self::curl($path,$data);
        $user = $user->data;

        if ($user && $user->users){
            if (isset($user->users[0])){
                $user = $user->users[0];
                $user->avatar = $user->foto_profil;
                return $user;
            }
        }
        return new \stdClass;

    }


    public static function meUpdate($data=[])
    {
        $path = '/me';
        $method = 'post';

        return self::curlRaw($path,$data,$method);
    }














    /**
     * call curl
     *
     * @return void
     * @author 
     **/
    public static function DirectCurl($path='/me',$data=[],$method='get')
    {

        return self::curl($path,$data,$method);

    }


    /**
     * Run cURL
     *
     * @return void
     * @author 
     **/
    private static function curl($path='/',$data=[],$method='get',$childToken=false)
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
        $request->setOption(CURLOPT_TIMEOUT, 12);

        if($childToken)
        {
            $request->setHeader('usertoken', $childToken );
        }
        else
        {
            if ( null != Cookie::get( config('auth.ssocookie') ) )
            {

                $request->setHeader('usertoken',  Cookie::get( config('auth.ssocookie') ) );
            }
            else
            {
                // dd(session('logid'));
                if( session('logid') )
                {
                    $user = Users::where('id',session('logid'))->select('token')->first();
                    // dd($user->token);
                    if($user && $user->token)
                    {
                        $request->setHeader('usertoken', $user->token);
                    }
                }
            }
        }
        
        try {

            $return = $request->send();

            if( json_decode($return) )
            {
                $return = json_decode($return);

                if($return->success)
                {
                    return $return;
                }
                else
                {
                    if(isset($return->code) && 35 ==$return->code )
                    {
                    exit( view('oa::errors.generic',['message'=>'App Unauthorized<br/> <a href="">Reload</a>']) );
                    }
                }
            }
            else
            {
              //return (object) $return;
              exit( view('oa::errors.generic',['message'=>$return.self::$appServer.'Bad Response received<br/> <a href="">Reload</a>']) );
            }
        }
        catch (\Exception $e)
        {
            exit( view('oa::errors.generic',['message'=>'Connection Time Out!<br/> <a href="">Reload</a>' . $e]) );   
        }
        
        return (object) $return;

    }
    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function curlRaw($path='/',$data=[],$method='post',$childToken=false)
    {

        $mdconfig = config('auth.md_sso');
        self::$appId = $mdconfig['APP_ID'];
        self::$appSecret = $mdconfig['APP_SECRET'];
        self::$appServer = $mdconfig['APP_SERVER'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$appServer . $path);
        

        $headers = [
                        "appid: ".self::$appId,
                        "appsecret: ".self::$appSecret
                    ];
        
        if($childToken)
        {
            $headers[] = "usertoken: $childToken";
        }
        else
        {
            if ( null != Cookie::get( config('auth.ssocookie') ) )
            {

                $headers[] = "usertoken: ".Cookie::get( config('auth.ssocookie') );
            }
            else
            {
                // dd(session('logid'));
                if( session('logid') )
                {
                    $user = Users::where('id',session('logid'))->select('token')->first();
                    // dd($user->token);
                    if($user && $user->token)
                    {
                        $headers[] = "usertoken: ".$user->token;
                    }
                }
            }
        }


        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $ulink = false;
        if (isset($data['avatar'])){

            $ulink = $data['avatar'];
            $data['avatar'] = new \CURLFile($data['avatar']);

        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);

        // output the response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        curl_close($curl);
        
        if ($ulink)
        {
            try{
                unlink($ulink);
            }
            catch(\Exception $e)
            {}
        }
        
        if( json_decode($return) )
        {
            return json_decode($return);
        }
        
        return (object) $return;

    }

    public static function can($module=false)
    {
        if ($module)
        {
            $moduleId = Modules::select(['id'])->where('name',$module)->first();
            if(!$moduleId)
            {
                return [];
            }

            $moduleId = $moduleId->id;
            
            $grants = ModulePermissions::where('module_id',$moduleId)
                        ->where('role',app()->OAuth->Auth()->role)
                        ->first();
            $can = [];

            if($grants)
            {
                if(isset($grants->read) && 1 == $grants->read){
                    $can[] = 'read';
                }
                if(isset($grants->write) && 1 == $grants->write){
                    $can[] = 'write';
                }
                if(isset($grants->edit) && 1 == $grants->edit){
                    $can[] = 'edit';
                }
                if(isset($grants->delete) && 1 == $grants->delete){
                    $can[] = 'delete';
                }
            }

            return $can;
        }
        return self::$can;
    }

}