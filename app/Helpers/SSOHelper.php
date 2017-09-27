<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Session;

class SSOHelper
{
	static $MDKey = '';
	static $MDServer = '';
	static $curl = '';
	
	function __construct(){
		$mdconfig = config('auth.md_sso');
		self::$MDKey = $mdconfig['APP_KEY'];
		self::$MDServer = $mdconfig['SERVER'];
	}

	/**
	 * [register description]
	 * @param  Request $request [description]
	 * @return json           [description]
	 *
	 * post data
	 * role
	 * username
	 * name
	 * email
	 * password
	 * jabatan
	 * phone_number
	 */
	public static function register(Request $request){
    	
        if( $request->has(['role', 'username', 'name', 'email','password','jabatan','phone_number']) ){

            $validator = Validator::make($request->all(), [
                'role' => 'required|max:191',
                'username' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'jabatan' => 'required',
                'phone_number' => 'required',
            ]);

            if ($validator->fails()) {
                
                $failedRules = $validator->failed();
                $return['status'] = 'failed';
                $return['message'] = 'validation error';
                $return['details'] = $failedRules;
            
            }else{

                $data = [
                    'role' => $request->input('role'),
                    'username' => $request->input('username'),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                    'jabatan' => $request->input('jabatan'),
                    'phone_number' => $request->input('phone_number')
                    ];

                $curl = new \anlutro\cURL\cURL;
                $request = $curl->newRequest('post',self::$MDServer . '/api/auth/register',$data)
                    ->setHeader('Accept-Charset', 'utf-8')
                    ->setHeader('Accept-Language', 'en-US')
                    ->setHeader('domainsecret', self::$MDKey);

                $return = json_decode($request->send());
            
            }

        }else{
            
            $return['status'] = 'failed';
            $return['message'] = 'incomplete data';

        }

        return json_encode($return);

    }

    /**
     * [activationUser description]
     * @param  String $token [description]
     * @param  String $email [description]
     * @return json        [description]
     */
    public static function activationUser($token = null,$email = null){
        
        if(isset($token) and $token !=null and isset($email) and $email != null){

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . '/api/auth/activation/'.$token.'/email/'.$email)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey);

            $return = json_decode($request->send());

        }else{

            $return['status'] = 'failed';
            $return['message'] = 'incomplete data';

        }

        return json_encode($return);
    }

    /**
     * [forgotPassword description]
     * @param  Request $request [description]
     * @return json           [description]
     * request data
     * $email
     */
    public static function forgotPassword(Request $request){
        
        if($request->has('email')){
            
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) { 

                $return['status'] = 'failed';
                $return['message'] = 'invalid mail format';

            }else{

                $data = [
                    'email' => $request->input('email')
                    ];

                $curl = new \anlutro\cURL\cURL;
                $request = $curl->newRequest('post',self::$MDServer . '/api/auth/forgotpassword',$data)
                    ->setHeader('Accept-Charset', 'utf-8')
                    ->setHeader('Accept-Language', 'en-US')
                    ->setHeader('domainsecret', self::$MDKey);

                $return = json_decode($request->send());

            }

        }

        return json_encode($return);

    }

    /**
     * [updatePassword description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * post data
     * email
     * password
     * token
     */
    public static function updatePassword(Request $request){

        if( $request->has(['email','password','token']) ){

            $data = [
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                    'token' => $request->input('token'),
                    ];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('post',self::$MDServer . '/api/auth/updatepassword',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey);

            $return = json_decode($request->send());

        }else{

            $return['status'] = 'failed';
            $return['message'] = 'invalid format';

        }

        return json_encode($return);

    }

    /**
     * [updateUser description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     * post data
     * email
     * password
     * token
     */
    public static function updateUser( $data =[] ){

        if( count( $data ) > 0 ){

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('post',self::$MDServer . '/api/updateuser',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if ( $return->status == 'success')
                return true;
            return false;

        }

    }


    /**
     * [login description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * post data
     * username
     * password
     */
    public static function login(Request $request){

        if( $request->has(['username','password']) ){

            $data = [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    ];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('post',self::$MDServer . '/api/auth/signin',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if($return->status == 'success'){

                session([
                    'clientid' => $return->message->clientid,
                    'clientsecret'=> $return->message->clientsecret
                    ]);

            }

        }else{

            $return['status'] = 'failed';
            $return['message'] = 'invalid format';

        }
        
        return json_encode($return);

    }

    public static function editProfil(){}

    public static function deactiveAccount(){}

    /**
     * [getUserDetail description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * session
     */
    public static function getUserDetail(){
        /**
         * if session failed start, you can add
         * 
         * \Illuminate\Session\Middleware\StartSession::class,
         * \Illuminate\View\Middleware\ShareErrorsFromSession::class, 
         *
         * on middleware section on kernel.php
         */

        if( session()->exists('clientid') and session()->exists('clientsecret')){

            $data = [];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . '/api/userinfo',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());

        }else{

            $return['status'] = 'failed';
            $return['message'] = 'authorize failed';

        }
        return json_encode($return);

    }

    /**
     * [logout description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     * clientid
     * clientsecret
     */
    public static function logout(){
        
        /**
         * if session failed start, you can add
         * 
         * \Illuminate\Session\Middleware\StartSession::class,
         * \Illuminate\View\Middleware\ShareErrorsFromSession::class, 
         *
         * on middleware section on kernel.php
         */

        if( session()->exists('clientid') and session()->exists('clientsecret')){

            $data = [];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . '/api/auth/signout',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if ( $return->status == 'success' )
                return true;
        }
        return false;
    }

    /**
     * [get Provinsi description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     * clientid
     * clientsecret
     */
    public static function getProvinsi($id = 0){
            $id = intval($id);
            $finalUrl = ( $id < 1 ) ? "/api/getprovinsi" : "/api/getprovinsi/$id";
            $finalUrl = self::$MDServer . $finalUrl;
            $data = []; 

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get', $finalUrl ,$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if ( $return->status == 'success' )
                return $return->message;

        return [];
    }

    /**
     * [get Provinsi description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     * clientid
     * clientsecret
     */
    public static function getKota($id = 0, $type = 'prov'){
            $id = intval($id);
            $finalUrl = ( $type == 'prov' ) ? "/api/getkota/prov/$id" : "/api/getkota/kota/$id";
            $finalUrl = self::$MDServer . $finalUrl;
            $data = []; 

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get', $finalUrl ,$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if ( $return->status == 'success' )
            {
                if( $type == 'kota' )
                {
                    return $return->message[0];
                }

                return $return->message;
            }

        return [];
    }

    /**
     * [getPublicProfile description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     */
    public static function getPublicProfile($str = NULL, $type = 'username'){

            $finalUrl = "/api/userpublic/$str/$type";
            $finalUrl = self::$MDServer . $finalUrl;
            $data = []; 

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get', $finalUrl ,$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());

            if ( $return->status == 'success' )
            {
                return $return->message;
            }

        return [];
    }



    /**
     * [Auth description]
     * @return bool           [description]
     *
     */
    public static function Auth()
    {
        $mdconfig = config('auth.md_sso');
        self::$MDKey = $mdconfig['APP_KEY'];
        self::$MDServer = $mdconfig['SERVER'];

        if( session()->exists('clientid') and session()->exists('clientsecret'))
        {
            $data = [];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . '/api/userinfo',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $user = ($request->send());
            $user = json_decode($user)->message;

            return $user;
        }

        return false;
    }


    /**
     * [getUserDetail description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * session
     */
    public static function SUGetUserDetail($str=NULL,$definer=NULL){
        /**
         * if session failed start, you can add
         * 
         * \Illuminate\Session\Middleware\StartSession::class,
         * \Illuminate\View\Middleware\ShareErrorsFromSession::class, 
         *
         * on middleware section on kernel.php
         */

        if( session()->exists('clientid') and session()->exists('clientsecret')){

            $data = [];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . "/api/s_getuser/$str/$definer",$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if ( $return->status == 'success' )
                return json_encode($return->message);

        }

        return false;

    }

    /**
     * [getUsers description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * session
     */
    public static function SUGetUsers($str=NULL,$definer=NULL){
        /**
         * if session failed start, you can add
         * 
         * \Illuminate\Session\Middleware\StartSession::class,
         * \Illuminate\View\Middleware\ShareErrorsFromSession::class, 
         *
         * on middleware section on kernel.php
         */

        if( session()->exists('clientid') and session()->exists('clientsecret')){

            $data = [];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . "/api/s_getusers/$str/$definer",$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = ($request->send());

            if ( json_decode($return)->status == 'success' )
                return json_encode(json_decode($return)->message);

            return [];

        }
    }

    /**
     * [SUAddUser description]
     * @param  Request $request [description]
     * @return bool           [description]
     *
     * session
     */
    public static function SUAddUser($data=NULL){
        /**
         * if session failed start, you can add
         * 
         * \Illuminate\Session\Middleware\StartSession::class,
         * \Illuminate\View\Middleware\ShareErrorsFromSession::class, 
         *
         * on middleware section on kernel.php
         */

        if( session()->exists('clientid') and session()->exists('clientsecret')){

            if ( !(self::$MDKey) )
            {
                $mdconfig = config('auth.md_sso');
                self::$MDKey = $mdconfig['APP_KEY'];
                self::$MDServer = $mdconfig['SERVER'];
            }

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('post',self::$MDServer . "/api/s_adduser",$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');
            $return = ($request->send());
            if ( json_decode($return)->status == 'success' )
                return true;

            return false;

        }
    }


    /**
     * [customLog description]
     * @param  Request $request [description]
     * @return json           [description]
     *
     * post data
     * username
     * password
     */
    public static function newsLog($log=''){

            if ( '' == self::$MDKey )
            {
                $mdconfig = config('auth.md_sso');
                self::$MDKey = $mdconfig['APP_KEY'];
                self::$MDServer = $mdconfig['SERVER'];
            }
            $data = ['log' => $log];

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('post',self::$MDServer . '/api/newslog',$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = json_decode($request->send());
            if($return->status == 'success')
                return true;

            return false;

    }

    public static function authorInfo($id = ''){

            if ( '' == self::$MDKey )
            {
                $mdconfig = config('auth.md_sso');
                self::$MDKey = $mdconfig['APP_KEY'];
                self::$MDServer = $mdconfig['SERVER'];
            }

            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . '/api/getwriterinfo/'.$id)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey);

            $return = json_decode($request->send());
            if($return->status == 'success')
                return $return->message;

            return false;

    }


    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function listMentors()
    {
        if( session()->exists('clientid') and session()->exists('clientsecret')){
            if ( !(self::$MDKey) )
            {
                $mdconfig = config('auth.md_sso');
                self::$MDKey = $mdconfig['APP_KEY'];
                self::$MDServer = $mdconfig['SERVER'];
            }
            $data = [];
            $filter = 'mentor';
            $table = 'role';
            $curl = new \anlutro\cURL\cURL;
            $request = $curl->newRequest('get',self::$MDServer . "/api/s_getusers/$filter/$table",$data)
                ->setHeader('Accept-Charset', 'utf-8')
                ->setHeader('Accept-Language', 'en-US')
                ->setHeader('domainsecret', self::$MDKey)
                ->setHeader('clientid',  session('clientid',''))
                ->setHeader('clientsecret', session('clientsecret',''))
                ->setHeader('clientheader', 'website');

            $return = ($request->send());
            if ( json_decode($return)->status == 'success' )
                return json_encode(json_decode($return)->message);

            return [];

        }
    }

}