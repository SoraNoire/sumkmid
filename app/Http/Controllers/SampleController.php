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

		$data = ['username' => 'admin','password' => 'pulogadung2017',];

		$curl = new \anlutro\cURL\cURL;
        $r = $curl->newRequest('post',$MDServer . '/api/auth/signin',$data)
            ->setHeader('Accept-Charset', 'utf-8')
            ->setHeader('Accept-Language', 'en-US')
            ->setHeader('domainsecret', $MDKey)
            ->setHeader('clientheader', 'website');
        $return = json_decode($r->send());

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