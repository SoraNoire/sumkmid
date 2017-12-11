<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SSOHelper as SSO;
use Image;

class memberController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
    }
    /**
     * Show User Setting page.
     * @return Response
     */
	public function userSetting(){
        $var['page'] = "userSetting";
		return view('page.userSetting')->with(['var' => $var]);
	}

    public function updateProfilePict(Request $req){
		
		$name = $req->input('nama');
		$email = $req->input('email');
		if ($req->file('photo')->isValid()) {
			$photo = $req->file('photo');
			$img = Image::make($photo);
			if (!is_dir(public_path("/assets/users"))) {
			    mkdir(public_path("/assets/users"), 0774, true);
			}
			$path = public_path("/assets/users/$email.png");
			$img->save($path);
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
		if(SSO::meUpdate($data)){
			return app()->SSO->Auth()->foto_profil;
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
		$update = SSO::meUpdate($data);
		if($update->success){
			return back()->with(['success' => 'true']);
		}
		return back()->with([ 'warnName' => 'oldPass', 'warnMsg' => 'Password Lama Anda Salah !']);
	}
}
