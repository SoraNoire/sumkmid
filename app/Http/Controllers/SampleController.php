<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use App\Helpers\SSOHelper as SSO;

class SampleController extends Controller
{

	function __construct(Request $request)
	{
		
	}

	private static function isGranted(Request $request)
	{
		try {
			if ( env('API_SECRET') == $request->headers->get('clientsecret') )
				return true;

		} catch (Exception $e) {
			return false;
		}
	}

	public function admin(Request $request)
	{
		$mdconfig = config('auth.md_sso');
		$MDKey = $mdconfig['APP_KEY'];
		$MDServer = $mdconfig['SERVER'];

		$data = ['username' => 'togi.verodsniksa@yahoo.com','password' => 'pulogadung2017'];

		$curl = new \anlutro\cURL\cURL;
        $r = $curl->newRequest('post',$MDServer . '/api/apps/login',$data)
            ->setHeader('Accept-Charset', 'utf-8')
            ->setHeader('Accept-Language', 'en-US')
            ->setHeader('domainsecret', $MDKey)
            ->setHeader('clientheader', 'website');

        $return = json_decode($r->send());
        if ( $return->status == 'failed' )
        {
        	echo $return->message;
        	die;
        }
		$request->session()->put('clientid', $return->message->clientid);
        $request->session()->put('clientsecret', $return->message->clientsecret);

        // dd($request->session());
		return redirect('/sample')->send();
	}

	public function sample(Request $request)
	{
		$response = $this->getMentors($request);
		return response($response);
	}

	public function appUpd()
	{
		$mdconfig = config('auth.md_sso');
		$MDKey = $mdconfig['APP_KEY'];
		$MDServer = $mdconfig['SERVER'];

		if (function_exists('curl_file_create')) { // php 5.5+
		  $cFile = curl_file_create('/home/whoami/glowinc.png');
		} else { // 
		  $cFile = '@' . realpath('/home/whoami/glowinc.png');
		}

		$data = [
					'email' => 'togi.verodsniksa@yahoo.com',
					'password' => 'pulogadung2017',
					'username' => 'sername',
					'name' => 'asljdhasdlas asodjasd',
					'profile_photo' => $cFile,
				];

		$headers = array(
						"Content-Type:multipart/form-data",
						"clientheader:website",
						"clientid:".session('clientid'),
						"clientsecret:".session('clientsecret'),
						"domainsecret:".$MDKey,
						); // cURL headers for file uploading
	    $postfields = array("filedata" => $cFile, "filename" => 'glowinc.png');
	    $ch = curl_init();
	    $options = array(
	        CURLOPT_URL => $MDServer . '/api/apps/profile/update',
	        CURLOPT_HEADER => true,
	        CURLOPT_POST => 1,
	        CURLOPT_HTTPHEADER => $headers,
	        CURLOPT_POSTFIELDS => $data,
	        CURLOPT_RETURNTRANSFER => true
	    ); 
	    curl_setopt_array($ch, $options);
	    $x = curl_exec($ch);
	    curl_close($ch);
	    echo $x;
	}	

	public function appReg()
	{

		$mdconfig = config('auth.md_sso');
		$MDKey = $mdconfig['APP_KEY'];
		$MDServer = $mdconfig['SERVER'];

		$data = [
					'email' => 'togi.verodsniksa@yahoo.com',
					'password' => 'pulogadung2017',
					'username' => 'sername',
					'name' => 'asljdhasdlas asodjasd',
				];

		$curl = new \anlutro\cURL\cURL;
        $r = $curl->newRequest('post',$MDServer . '/api/apps/register',$data)
            ->setHeader('Accept-Charset', 'utf-8')
            ->setHeader('Accept-Language', 'en-US')
            ->setHeader('domainsecret', $MDKey)
            ->setHeader('clientheader', 'website')
            ->setHeader('clientid',  session('clientid',''))
            ->setHeader('clientsecret', session('clientsecret',''))
            ->setHeader('clientheader', 'website');;
        echo ($r->send());die;
        $return = json_decode($r->send());
        return response('ok');
	}




	private function getMentors(Request $request)
	{
		$mentors = SSO::listMentors();
		return ($mentors);
	}

	public function addmentor()
	{
		$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random = substr(str_shuffle($str),0,10);
		$data = [
			'name'=> $random,
			'role'=> 'mentor',
			'email' => $random.'@xmail.com',
			'username' => $random,
			'password' => $random,
			'description' => "{}",
		];

		SSO::SUAddUser($data);
		return response('ok');// redirect('/sample')->send();
	}

	

}