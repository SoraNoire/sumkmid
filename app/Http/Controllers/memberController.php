<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SSOHelper as SSO;
use Modules\Blog\Entities\Option;
use Image;
use View;

class memberController extends Controller
{
    function __construct()
    {
    	$var['page'] = 'Sahabat UMKM Indonesia';

		$analytic = Option::where('key', 'analytic')->first()->value ?? '';
        $fb_pixel = Option::where('key', 'fb_pixel')->first()->value ?? '';
        $link_fb = Option::where('key', 'link_fb')->first()->value ?? '';
        $link_tw = Option::where('key', 'link_tw')->first()->value ?? '';
        $link_ig = Option::where('key', 'link_ig')->first()->value ?? '';
        $link_yt = Option::where('key', 'link_yt')->first()->value ?? '';        
        $link_in = Option::where('key', 'link_in')->first()->value ?? '';
        $link_gplus = Option::where('key', 'link_gplus')->first()->value ?? '';
        $footer_desc = Option::where('key', 'footer_desc')->first()->value ?? '';
        $email_info = Option::where('key', 'email')->first()->value ?? config('app.email_info');

        View::share('var', $var);
        View::share('analytic', $analytic);
        View::share('fb_pixel', $fb_pixel);
        View::share('link_fb', $link_fb);
        View::share('link_ig', $link_ig);
        View::share('link_tw', $link_tw);
        View::share('link_yt', $link_yt);
        View::share('link_gplus', $link_gplus);
        View::share('link_in', $link_in);
        View::share('footer_desc', $footer_desc);
        View::share('email_info', $email_info);
    }
    /**
     * Show User Setting page.
     * @return Response
     */
	public function userSetting(){
        $var['page'] = "userSetting";

        return "anu";
        
        $user = app()->OAuth->Auth(app()->OAuth->Auth()->token);
        if($user && $user->success){
            $user = $user->data;            
        }else{
            $user = app()->OAuth->auth();
        }
        $var['user'] = $user;

		return view('page.userSetting')->with(['var' => $var]);
	}

    public function updateProfilePict(Request $req){
		
		$name = $req->input('nama');
		$email = $req->input('email');
		if ($req->file('photo')->isValid()) {
			$photo = $req->file('photo');
			
            $filename = $email.'.png';
            $path = public_path("/assets/users/".$filename);
			$img = Image::make($photo->getRealPath())->save($path);
			// if (!is_dir(public_path("/assets/users"))) {
			//     mkdir(public_path("/assets/users"), 0774, true);
			// }
			// $path = public_path("/assets/users/$email.png");
			// $img->save($path);
			// $photo = file_get_contents($path);
			// unlink($path);

		}else{
			return 'photo tidak valid';
		}

		$data = [   
			'name'=> $name,
			'email' => $email,
			'avatar'=> $path
		];
        $user = app()->OAuth->Auth(app()->OAuth->Auth()->token);
        if($user && $user->success){
            $user = $user->data;            
        }else{
            $user = app()->OAuth->auth();
        }
		if(app()->OAuth::meUpdate($data)){
			return $user->foto_profil;
		}else{
			return 'fail';
		}
		
	}
    public function saveUserSetting(Request $req){
		$name = $req->input('nama');
		$email = $req->input('email');
		$notelp = $req->input('nomorTelepon');

		if($name == ''){
			$warnName = 'name';
			$warnMsg = 'Anda Belum Memasukan Nama';
			return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg]);
		}
		if(strlen($name) < 6){
			$warnName = 'name';
			$warnMsg = 'Nama Terlalu Pendek';
			return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg ]);
		}
		if($email == ''){
			$warnName = 'email';
			$warnMsg = 'Anda Belum Memasukan Email';
			return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg ]);
		}
		if(strlen($notelp) < 8){
			$warnName = 'nomorTelepon';
			$warnMsg = 'Nomor Telepon Anda Terlalu Pendek';
			return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg ]);
		}
		$oldPass = $req->input('old_password');
		$newPass = $req->input('new_password');
		$confNewPass = $req->input('password_confirmation');

		$data = [
			'name'=>$name,
			'email' => $email,
			'phone' => $notelp
		];
		if($oldPass != ''){
			if(strlen($oldPass) < 6){
				$warnName = 'oldPass';
				$warnMsg = 'Password Minimal 6 Kata';
				return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg]);
			}
			if(strlen($newPass) < 6){
				$warnName = 'newPass';
				$warnMsg = 'Password Baru Minimal 6 Kata';
				return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg]);
			}
			if($confNewPass != $newPass){
				$warnName = 'password_confirmation';
				$warnMsg = 'Konfirmasi Password Baru Tidak Sama';
				return back()->with(['warnName' => $warnName, 'warnMsg' => $warnMsg]);
			}
			$data = [
				'name'=> $name,
				'email' => $email,
				'phone' => $notelp,
				'old_password' => $oldPass,
				'password' => $newPass,
				'password_confirmation' => $confNewPass
			];
		}
		$update = app()->OAuth::meUpdate($data);
		if($update->success){
			return back()->with(['success' => 'true']);
		}
		return back()->with([ 'warnName' => 'oldPass', 'warnMsg' => 'Password Lama Anda Salah !']);
	}
}
