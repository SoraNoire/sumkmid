<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SSOHelper as SSO;
use App\Helpers\PublicHelper as Pubhelp;
use Rabbit\SahabatUser\Models\Users;	
use Rabbit\SahabatUser\Models\Kota;
use Rabbit\SahabatUser\Models\Provinsi;
use Rabbit\SahabatUser\Models\UserMeta;
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

	public function detailUserEdit(){

		$alamat['kota'] = Kota::get();
        $alamat['provinsi'] = Provinsi::get();

        self::meta_user();
		$user = app()->OAuth::Auth();
		$data = [
                    'usaha' => Pubhelp::listUsaha(),
                    'user' => $user
        ];

        // dd($user);
        return view('page.user.completion1', $data)->with(['alamat' => $alamat]);
		
	}

	public function goSaveMetaUser(Request $request){

        $this->completionSave($request,app()->OAuth::Auth()->id);
        $request->session()->flash('swal', (object)['status'=>'success','message'=>'Data Berhasil di Perbarui.']);

        return back();

    }


	public function meta_user($id = false){
        $idu = (false!==$id)? $id : app()->OAuth::Auth()->id;
        $userMeta = UserMeta::where('user_id',$idu)->get();
        $userData = [];
        $user = app()->OAuth::$Auth;
        $userMeta->map(
            function($meta) use(&$userData,&$user,$idu){
                if( false === $idu && 'type_user' == $meta->meta_key )
                {
                    $user->role = $meta->meta_value;
                }
                else{
                    // $user->{$meta->meta_key} = $meta->meta_value;
                    $userData[$meta->meta_key] = $meta->meta_value;
                }
            }
        );
        if ($id) {
            return (object)$userData;
        }
        app()->OAuth::$Auth->data = (object)$userData;
        unset($user);
    }

    public function completionSave(Request $request,$id=false){
        // save 'kota lahir'
        if($request->input('kota_lahir')){
        	$this->validate($request,[
		        'kota_lahir' => 'required'
		    ],[   
	            'kota_lahir.required'    => 'Kota Kelahiran Wajib di Isi'
        	]);
            self::add_or_update_meta('kota_lahir',$request->input('kota_lahir'),$id);
        }

        // save 'tahun lahir'
        if($request->input('tahun_lahir')){
            $tahunLahir = $request->input('tahun_lahir')
                            .'/'.$request->input('bulan_lahir')
                            .'/'.$request->input('tanggal_lahir');
			$this->validate($request,[
		        'tahun_lahir' => 'required|numeric',
		        'bulan_lahir' => 'required|numeric',
		        'tanggal_lahir' => 'required|numeric',
		    ],[   
	            'tahun_lahir.required'    => 'Tahun Kelahiran Wajib di Isi',
	            'bulan_lahir.required'    => 'Bulan Kelahiran Wajib di Isi',
	            'tanggal_lahir.required'    => 'Tanggal Kelahiran Wajib di Isi',
	            'tahun_lahir.numeric'    => 'Tahun Kelahiran Salah',
	            'bulan_lahir.numeric'    => 'Bulan Kelahiran Salah',
	            'tanggal_lahir.numeric'    => 'Tanggal Kelahiran Salah',
        	]);

            self::add_or_update_meta('tanggal_lahir',$tahunLahir,$id);
        }

        // save 'alamat'
        if($request->input('alamat')){

        	$this->validate($request,[
		        'alamat' => 'required',
		    ],[   
	            'alamat.required'    => 'Alamat Wajib di Isi',
        	]);

            self::add_or_update_meta('alamat',$request->input('alamat'),$id);
        }

        // save 'provinsi'
        if($request->input('provinsi'))
        {
        	$this->validate($request,[
		        'provinsi' => 'required',
		    ],[   
	            'provinsi.required'    => 'Provinsi Wajib di Isi',
        	]);
            self::add_or_update_meta('provinsi',$request->input('provinsi'),$id);
        }

        // save 'kota'
        if($request->input('kota'))
        {
        	$this->validate($request,[
		        'kota' => 'required',
		    ],[   
	            'kota.required'    => 'Kota Wajib di Isi',
        	]);
            self::add_or_update_meta('kota',$request->input('kota'),$id);
        }

        // save 'telepon'
        if($request->input('telepon'))
        {
        	$this->validate($request,[
		        'telepon' => 'required|numeric',
		    ],[   
	            'telepon.required'    => 'Telepon Wajib di Isi',
	            'telepon.numeric'    => 'Nomor Telepon Harus Angka'
        	]);
            self::add_or_update_meta('telepon',$request->input('telepon'),$id);
        }

        // save 'type_user'
        if($request->input('type_user'))
        {
            $typeUser = ( 'ya' == $request->input('type_user') ) ? 'umkm' : 'perorangan';
            self::add_or_update_meta('type_user',$typeUser,$id);
            // delete umkm data if any
            UserMeta::whereIn('meta_key',['nama_usaha','jenis_usaha','lama_berdiri','omzet'])
                      ->where('user_id',app()->OAuth::Auth()->id)->delete();
        }

        // save 'nama_usaha'
        if($request->input('nama_usaha'))
        {
        	$this->validate($request,[
		        'nama_usaha' => 'required',
		    ],[   
	            'nama_usaha.required'    => 'Nama Usaha Wajib di Isi',
        	]);
            self::add_or_update_meta('nama_usaha',$request->input('nama_usaha'),$id);
        }

        // save 'jenis_usaha'
        if($request->input('jenis_usaha'))
        {
        	$this->validate($request,[
		        'jenis_usaha' => 'required',
		    ],[   
	            'jenis_usaha.required'    => 'Jenis Usaha Wajib di Isi',
        	]);
            self::add_or_update_meta('jenis_usaha',$request->input('jenis_usaha'),$id);
        }

        // save 'lama_berdiri'
        if($request->input('lama_berdiri'))
        {
        	$this->validate($request,[
		        'lama_berdiri' => 'required|numeric',
		    ],[   
	            'lama_berdiri.required'    => 'Tahun Berdiri Wajib di Isi',
	            'lama_berdiri.numeric'    => 'Tahun yang Anda Masukan Salah (Wajib Angka)'
        	]);
            self::add_or_update_meta('lama_berdiri',$request->input('lama_berdiri'),$id);
        }

        // save 'omzet'
        if($request->input('omzet'))
        {
            if( 'null' != $request->input('omzet') )
            {
            $this->validate($request,[
		        'omzet' => 'required',
		    ],[   
	            'omzet.required'    => 'Omzet Wajib di Isi',
        	]);
              self::add_or_update_meta('omzet',$request->input('omzet'),$id);
            }
        }

        if($request->file('foto_ktp'))
        {
            $path = '/cr/ktp/';
            $filename = rand(3,977) . (md5(date('YMDHis'))) . '.jpg';
            if (!file_exists( storage_path($path) )) {
                mkdir( storage_path($path) , 0750, true);
                file_put_contents(storage_path($path.".gitignore"),"*");
            }
            $file = $request->file('foto_ktp');
            $file->move( storage_path( $path ),$filename );
            $img = \Image::make( storage_path( $path . $filename) );
            $img->save( storage_path( $path . $filename) , 75);

            self::add_or_update_meta('foto_ktp',$filename,$id);
        }

        // save 'informasi_usaha'
        if($request->input('informasi_usaha'))
        {

            if ( 1 == sizeof($request->input('info')) )
            {
              foreach ($request->input('info') as $key => $i) {
                  if($i && '' != $i){
                    self::add_or_update_meta('informasi_usaha',json_encode($request->input('info')),$id);
                  }
              }

            }
            else
            {
              $info = [];
              foreach ($request->input('info') as $key => $value) {
                if( null != $value && 'null' != $key && '' != $value && '' != $key)
                {
                    $info[$key] = $value;
                }
              }
              self::add_or_update_meta('informasi_usaha',json_encode($info),$id);
            }
        }


        // save 'usaha_tetap'
        if($request->input('usaha_tetap'))
        {

            self::add_or_update_meta('usaha_tetap',$request->input('usaha_tetap'),$id);
        }
        // save 'kelengkapan_dokumen'
        if($request->input('kelengkapan_dokumen'))
        {

            self::add_or_update_meta('kelengkapan_dokumen',$request->input('kelengkapan_dokumen'),$id);
        }
        // save 'tempat_usaha'
        if($request->input('tempat_usaha'))
        {

            self::add_or_update_meta('tempat_usaha',$request->input('tempat_usaha'),$id);
        }
        // save 'adm_keuangan'
        if($request->input('adm_keuangan'))
        {

            self::add_or_update_meta('adm_keuangan',$request->input('adm_keuangan'),$id);
        }
        // save 'akses_perbankan'
        if($request->input('akses_perbankan'))
        {

            self::add_or_update_meta('akses_perbankan',$request->input('akses_perbankan'),$id);
        }



        // save 'kuisioner_mengapa'
        if($request->input('kuisioner_mengapa'))
        {
        	$this->validate($request,[
		        'kuisioner_mengapa' => 'required',
		    ],[   
	            'kuisioner_mengapa.required'    => 'Jawaban Wajib Di isi',
        	]);
            self::add_or_update_meta('kuisioner_mengapa',$request->input('kuisioner_mengapa'),$id);
        }

        // save 'kuisionar_harapan'
        if($request->input('kuisioner_harapan'))
        {
        	$this->validate($request,[
		        'kuisioner_harapan' => 'required',
		    ],[   
	            'kuisioner_harapan.required'    => 'Jawaban Wajib Di isi',
        	]);
            self::add_or_update_meta('kuisioner_harapan',$request->input('kuisioner_harapan'),$id);
        }

        // save 'tos_terima'
        if($request->input('tos_terima'))
        {

            $tos = ( 'on' == $request->input('tos_terima') ) ? 1 : 0;
            if( $request->input('kuisioner_harapan') && $request->input('kuisioner_mengapa')  )
            {
            	self::add_or_update_meta('tos_terima',$tos,$id);
            }
        }


        if($id)
        {
            return back();
        }
        return redirect('/')->send();
    }


    private static function add_or_update_meta($key,$val,$id=false){
        $id = ( false!==$id ) ? $id : app()->OAuth::Auth()->id;
        if(UserMeta::where('user_id',$id)->where('meta_key',$key)->first())
        {
            UserMeta::where('user_id',$id)
                    ->where('meta_key',$key)
                    ->update(['meta_value'=>$val]);
        }
        else
        {
            $insert = [
                        'user_id' => $id,
                        'meta_key' => $key,
                        'meta_value' => $val
                    ];
            UserMeta::insert($insert);
        }
    }

}
