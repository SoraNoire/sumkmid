<?php

namespace Rabbit\SahabatUser\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use App\Helpers\PublicHelper as Pubhelp;
// use Rabbit\OAuthClient\Utils\OAuth;
use Rabbit\SahabatUser\Models\Users;
use Rabbit\SahabatUser\Models\Kota;
use Rabbit\SahabatUser\Models\Provinsi;
use Rabbit\SahabatUser\Models\UserMeta;
// use Rabbit\OAuthClient\Models\ModulePermissions;

class SahabatUserController extends Controller
{

    var $page = 1;
    var $perPage = 12;
    var $offset = 0;
    var $returnInput;
    var $search=false;
    static $step = 1;

    public function __construct(Request $request)
    {
        if( $request->input('page') )
        {
            $this->page = $request->input('page') ?? 1;
            $this->page = ($this->page < 0) ? 1 : $this->page;
            if ($this->page >= 2)
            {
                $this->offset = ($this->perPage * ($this->page-1) );
            }
        }
    }

    public function users(Request $request)
    {

        if ($this->search)
        {
            $userSum = Users::where('name','like',"%$this->search%")
                                ->orWhere('username','like',"%$this->search%")
                                ->count();
            $users = Users::where('name','like',"%$this->search%")
                                ->orWhere('username','like',"%$this->search%")
                                ->offset($this->offset)
                                ->take($this->perPage)
                                ->get();
            view()->share('search_query',$this->search);
        }
        else
        {
            $userSum = Users::count();
            $users = Users::offset($this->offset)->take($this->perPage)->get();   
        }
        $page = new \stdClass;
        $page->sum = ceil($userSum / $this->perPage);
        $page->usersum = $userSum;
        $page->userstart = $this->offset;
        $page->perpage = $this->perPage;
        $page->current = $this->page;
        $page->start = ( $this->page - 3 );
        $page->end = ( ($this->page+3) > $page->sum ) ? $page->sum : $this->page+3;

        if ($page->current < 4 )
        {
            $page->start = 1;
            if ($page->sum > 1 && $page->sum <= $page->start)
            {
                $page->end = ($page->end + (4-$page->start));
            }
        }

        return view('shb::backend.users.index',['page'=>$page,'users'=>$users]);
    }

    // private static function listUsaha()
    // {
    //     $usaha = [
    //                 'Aplikasi Dan Pengembang Permainan',
    //                 'Arsitektur',
    //                 'Desain Interior',
    //                 'Desain Komunikasi Visual',
    //                 'Desain Produk',
    //                 'Fashion',
    //                 'Film, Animasi, Dan Video',
    //                 'Fotografi',
    //                 'Kriya',
    //                 'Kuliner',
    //                 'Musik',
    //                 'Penerbitan',
    //                 'Periklanan',
    //                 'Seni Pertunjukan',
    //                 'Seni Rupa',
    //                 'Televisi Dan Radio'
    //         ];
    //     return (object)$usaha;
    // }

    private static function meta_user($id = false){
        $idu = (false!==$id)? $id : app()->OAuth::Auth()->id;
        $userMeta = UserMeta::where('user_id',$idu)->get();
        $userData = [];
        $user = app()->OAuth::$Auth;
        $userMeta->map(
            function($meta) use(&$userData,&$user,$id){
                if( false === $id && 'type_user' == $meta->meta_key )
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

    private static function check_steps()
    {
        $user = app()->OAuth::Auth();
        if (
                $user->data && isset($user->data->kota_lahir) && isset($user->data->tanggal_lahir)
                && isset($user->data->alamat) && isset($user->data->telepon)
        )
        {
            self::$step = 2;

            if( 'perorangan' == $user->role)
            {
                if( isset($user->data->foto_ktp) )
                {
                    self::$step = 4;
                }
            }
            else
            {
                if( 
                    isset($user->data->foto_ktp) && isset($user->data->nama_usaha) && isset($user->data->jenis_usaha)
                    && isset($user->data->tahun_berdiri) && isset($user->data->info_usaha) && isset($user->data->omzet)
                )
                {
                    self::$step = 3;

                    if( 
                        isset($user->data->usaha_tetap) && isset($user->data->kelengkapan_dokumen) && isset($user->data->tempat_usaha) && isset($user->data->adm_keuangan) && isset($user->data->akses_perbankan)  
                    )
                    {
                        self::$step = 4;
                    }
                }   
            }


            if( 4 == self::$step && isset($user->data->kuisioner_mengapa) && isset($user->data->kuisioner_harapan ) && isset($user->data->tos_terima) && 1 == $user->data->tos_terima  )
            {
                $meta = UserMeta::where('user_id',app()->OAuth::Auth()->id)->where('meta_key','data_completed')->first();
                if( ! $meta )
                {
                    UserMeta::where('user_id',app()->OAuth::Auth()->id)->where('meta_key','data_completed')->delete();
                    UserMeta::insert(['user_id'=>app()->OAuth::Auth()->id, 'meta_key'=>'data_completed','meta_value'=>1]);
                }
                return redirect('/')->send();
            }

        }
    }


    public function completeData(Request $request,$i)
    {
        if( 'admin' == app()->OAuth::Auth()->role )
        {
            return redirect('/')->send();   
        }

        $alamat['kota'] = Kota::get();
        $alamat['provinsi'] = Provinsi::get();

        self::meta_user();
        self::check_steps();
        $user = app()->OAuth::Auth();
        if($i != self::$step)
        {
            return redirect( route('SHB.complete_data',self::$step))->send();
        }

        view()->share(['email_info'=>'']);

        $data = [
                    'usaha' => Pubhelp::listUsaha(),
                    'user' => $user
        ];
        switch ($i) {
            case 1:
                return view('shb::frontend.member.completion1', $data)->with(['alamat' => $alamat]);
                break;
            case 2:
                return view('shb::frontend.member.completion2', $data);
                break;
            case 3:
                return view('shb::frontend.member.completion3', $data);
                break;
            case 4:
                return view('shb::frontend.member.completion4', $data);
                break;
            default:
                return redirect('/');
                break;
        }


    }

    public function completionSave(Request $request,$id=false){

        if($request->input('step') == 'step1'){
            // save 'kota lahir'
            $this->validate($request,[
                'kota_lahir' => 'required',
                'tahun_lahir' => 'required|numeric',
                'bulan_lahir' => 'required|numeric',
                'tanggal_lahir' => 'required|numeric',
                'provinsi' => 'required',
                'kota' => 'required',
                'alamat' => 'required',
                'telepon' => 'required|numeric',
            ],[
                'kota_lahir.required'       => 'Kota Kelahiran Wajib di Isi',
                'tahun_lahir.required'      => 'Tahun Kelahiran Wajib di Isi',
                'bulan_lahir.required'      => 'Bulan Kelahiran Wajib di Isi',
                'tanggal_lahir.required'    => 'Tanggal Kelahiran Wajib di Isi',
                'tahun_lahir.numeric'       => 'Tahun Kelahiran Wajib di Isi',
                'bulan_lahir.numeric'       => 'Bulan Kelahiran Wajib di Isi',
                'tanggal_lahir.numeric'     => 'Tanggal Kelahiran Wajib di Isi',
                'provinsi.required'         => 'Provinsi Wajib di Isi',
                'kota.required'             => 'Kota Wajib di Isi',
                'alamat.required'           => 'Alamat Wajib di Isi',
                'telepon.required'          => 'Telepon Wajib di Isi',
                'telepon.numeric'           => 'Nomor Telepon Harus Angka'
            ]);

            self::add_or_update_meta('kota_lahir',$request->input('kota_lahir'),$id);

            // save 'tahun lahir'
            $tahunLahir = $request->input('tahun_lahir')
                            .'/'.$request->input('bulan_lahir')
                            .'/'.$request->input('tanggal_lahir');

            $checkDate = checkdate($request->input('bulan_lahir'),$request->input('tanggal_lahir'),$request->input('tahun_lahir'));

            if($checkDate == false){
                return Redirect::back()->withErrors(['Tanggal Salah !']);
            }
            //save 'tanggal_lahir'
            self::add_or_update_meta('tanggal_lahir',$tahunLahir,$id);

            // save 'alamat'
            self::add_or_update_meta('alamat',$request->input('alamat'),$id);

            // save 'provinsi'
            self::add_or_update_meta('provinsi',$request->input('provinsi'),$id);

            // save 'kota'
            self::add_or_update_meta('kota',$request->input('kota'),$id);

            // save 'telepon'
            self::add_or_update_meta('telepon',$request->input('telepon'),$id);

            if($id){
                return back();
            }
            return redirect('/')->send();
        }

        if($request->input('step') == 'step2'){

            // save 'type_user'
            if($request->input('type_user'))
            {
                $typeUser = ( 'ya' == $request->input('type_user') ) ? 'umkm' : 'perorangan';

                if($typeUser == 'umkm'){

                    $this->validate($request,[
                        'nama_usaha' => 'required',
                        'jenis_usaha' => 'required',
                        'tahun_berdiri' => 'required|numeric',
                        'omzet' => 'required',
                        'foto_ktp' => 'required|image',
                        'info_usaha.*' => 'required|min:1',
                        'info_usaha.Telepon' => 'numeric',
                    ],[   
                        'nama_usaha.required'    => 'Nama Usaha Wajib di Isi',
                        'jenis_usaha.required'    => 'Jenis Usaha Wajib di Isi',
                        'tahun_berdiri.required'    => 'Tahun Bediri Wajib di Isi',
                        'tahun_berdiri.numeric'    => 'Tahun Bediri Wajib Angka',
                        'omzet.required'    => 'Perkiraan Omzet Wajib di Isi',
                        'foto_ktp.required'    => 'Foto KTP Wajib di Isi',
                        'foto_ktp.image'    => 'Foto KTP Salah',
                        'info_usaha.*.required' => 'Informasi Usaha Minimal 1',
                        'info_usaha.Telepon.numeric' => 'Nomor Telepon Usaha Harus Angka'
                    ]);

                    // save 'nama_usaha'
                    self::add_or_update_meta('nama_usaha',$request->input('nama_usaha'),$id);

                    // save 'jenis_usaha'
                    self::add_or_update_meta('jenis_usaha',$request->input('jenis_usaha'),$id);

                    // save 'tahun_berdiri'
                    self::add_or_update_meta('tahun_berdiri',$request->input('tahun_berdiri'),$id);

                    // save 'omzet'
                    self::add_or_update_meta('omzet',$request->input('omzet'),$id);
                }else{
                    $this->validate($request,[
                        'foto_ktp' => 'required|image',
                    ],[
                        'foto_ktp.required'    => 'Foto KTP Wajib di Isi',
                        'foto_ktp.image'    => 'Foto KTP Salah'
                    ]);
                    self::add_or_update_meta('type_user',$typeUser,$id);
                    // delete umkm data if any
                    UserMeta::whereIn('meta_key',['nama_usaha','jenis_usaha','tahun_berdiri','omzet'])
                              ->where('user_id',app()->OAuth::Auth()->id)->delete();         
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
            // save 'info_usaha'
            if($request->input('info_usaha')){
                if ( 1 == sizeof($request->input('info_usaha')) )
                {
                  foreach ($request->input('info_usaha') as $key => $i) {
                      if($i && '' != $i){
                        self::add_or_update_meta('info_usaha',json_encode($request->input('info_usaha')),$id);
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
                  self::add_or_update_meta('info_usaha',json_encode($info),$id);
                }
            }

            if($id){
                return back();
            }
            return redirect('/')->send();
        }

        if($request->input('step') == 'step3'){

            $this->validate($request,[
                'usaha_tetap' => 'required',
                'kelengkapan_dokumen' => 'required',
                'tempat_usaha' => 'required',
                'adm_keuangan' => 'required',
                'akses_perbankan' => 'required',

            ],[   
                'usaha_tetap.required'    => 'Pertanyaan 1 Belum Terjawab',
                'kelengkapan_dokumen.required'    => 'Pertanyaan 2 Belum Terjawab',
                'tempat_usaha.required'    => 'Pertanyaan 3 Belum Terjawab',
                'adm_keuangan.required'    => 'Pertanyaan 4 Belum Terjawab',
                'akses_perbankan.required'    => 'Pertanyaan 5 Belum Terjawab',
            ]);

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
        }

        if($request->input('step') == 'step4'){

            $this->validate($request,[
                'kuisioner_mengapa' => 'required',
                'kuisioner_harapan' => 'required',
                'tos_terima' => 'required',

            ],[   
                'kuisioner_mengapa.required'    => 'Alasan Mengapa Wajib di Isi',
                'kuisioner_harapan.required'    => 'Harapan Wajib di Isi',
                'tos_terima.required'    => 'Anda Wajib Menyetujui Persyaratan Dibawah !',
            ]);
            // save 'kuisioner_mengapa'
            if($request->input('kuisioner_mengapa'))
            {

                self::add_or_update_meta('kuisioner_mengapa',$request->input('kuisioner_mengapa'),$id);
            }

            // save 'kuisionar_harapan'
            if($request->input('kuisioner_harapan'))
            {

                self::add_or_update_meta('kuisioner_harapan',$request->input('kuisioner_harapan'),$id);
            }

            // save 'tos_terima'
            if($request->input('tos_terima'))
            {

                $tos = ( 'on' == $request->input('tos_terima') ) ? 1 : 0;
                if( $request->input('kuisioner_harapan') && $request->input('kuisioner_mengapa')  )
                {
                $request->session()->flash('swal', (object)['status'=>'success','message'=>'Selamat Profil Anda Sudah Lengkap, Terimakasih']);
                  self::add_or_update_meta('tos_terima',$tos,$id);
                }
            }
        }

        if($id)
        {
            return back();
        }
        return redirect('/')->send();
    }


    private static function add_or_update_meta($key,$val,$id=false)
    {
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


    /**
     * delete user
     *
     * @return void
     * @author 
     **/
    public function deleteUser($id)
    {
        $myId = app()->OAuth::Auth()->id;
        if( $id != $myId)
        {
            // check role
            $role = app()->OAuth->user($id)->role ?? false;
            if('admin' != $role)
            {
                Users::where('id',$id)->delete();
            }
        }
        
        return redirect(route('panel.user__index'));
    }

    public function viewUser($id)
    {
        $user = Users::select(['master_id'])->where('id',$id)->first();
        if(!$user)
        {
            return redirect(route('SHB.dashboard'))->send();
        }
        $masterId = $user->master_id;

        $user = app()->OAuth->user($masterId);

        if(!$user || !isset($user->id))
        {
            // possible deleted from oauth
            Users::where('master_id',$masterId)->delete();
            return back();
        }

        // swap id
        $user->id = $id;
        $user->master_id = $masterId;
        
        $meta = self::meta_user($id);
        if(!$user)
        {
            return back();
        }
        $user->data = $meta;
        $data =[
            'user' => $user,
            'page' => 'Edit User'
        ];
        return view('shb::backend.users.edit',$data);
    }

    public function viewUserDetail($id)
    {
        $user = Users::where('id',$id)->first();
        $meta = self::meta_user($id);
        if(!$user || !isset($meta->type_user))
        {
            return back();
        }
        $user->data = $meta;
        $data =[
            'user' => $user,
            'page' => 'Edit User'
        ];
        if( 'perorangan' == $meta->type_user)
        {
            return view('shb::backend.users.edit-detail-perorangan',$data);
        }
        else
        {
            return view('shb::backend.users.edit-detail',$data);
        }
        return back();
    }

    public function updateUser(Request $request,$id)
    {
        $myId = app()->OAuth::Auth()->id;
        if( $id == $myId)
        {
            $this->completionSave($request,$id);
        }
        else
        {
            // check role
            $role = app()->OAuth->user($id)->role ?? false;
            if('admin' != $role)
            {
                $this->completionSave($request,$id);
            }
        }
        
        
        return redirect(route('panel.user__index'));
    }


    public function exportUsers(Request $request)
    {
        $type = $request->input('format') ?? 'xls';

        // $users = DB::table('users')
        //         ->select(['*'])
        //         // ->leftJoin('user_meta', 'users.id', '=', 'user_meta.user_id')
        //         ->join('user_meta', function ($join) {
        //             $join->select(['meta'])
        //             $join->on('users.id', '=', 'contacts.user_id');
        //         })
        //         ->get();

        $users = DB::select("
                            select users.id,
                                    users.username as nama_pengguna,
                                    users.name as nama_lengkap,
                                    users.description as biografi,
                                    tempat_lahir.meta_value as tempat_lahir,
                                    tanggal_lahir.meta_value as tanggal_lahir,
                                    alamat.meta_value as alamat,
                                    kota.meta_value as kota,
                                    provinsi.meta_value as provinsi,
                                    telepon.meta_value as telepon,
                                    type_user.meta_value as type_user,
                                    nama_usaha.meta_value as nama_usaha,
                                    jenis_usaha.meta_value as jenis_usaha,
                                    lama_berdiri.meta_value as tanggal_berdiri,
                                    omzet.meta_value as omzet,
                                    informasi_usaha.meta_value as informasi_usaha,
                                    berusaha_tetap.meta_value as berusaha_tetap,
                                    dokumen_usaha.meta_value as dokumen_usaha,
                                    tempat_usaha.meta_value as asset_tempat,
                                    adm_keuangan.meta_value as administrasi_keuangan,
                                    akses_perbankan.meta_value as akses_perbankan,
                                    kuisioner_mengapa.meta_value as alasan_bergabung,
                                    kuisioner_harapan.meta_value as harapan_bergabung

                            from users
                            
                            LEFT JOIN user_meta AS tempat_lahir ON tempat_lahir.user_id=users.id
                                AND tempat_lahir.meta_key = 'kota_lahir'

                            LEFT JOIN user_meta AS tanggal_lahir ON tanggal_lahir.user_id=users.id
                                AND tanggal_lahir.meta_key = 'tanggal_lahir'

                            LEFT JOIN user_meta AS alamat ON alamat.user_id=users.id
                                AND alamat.meta_key = 'alamat'

                            LEFT JOIN user_meta AS kota ON kota.user_id=users.id
                                AND kota.meta_key = 'kota'

                            LEFT JOIN user_meta AS provinsi ON provinsi.user_id=users.id
                                AND provinsi.meta_key = 'provinsi'

                            LEFT JOIN user_meta AS telepon ON telepon.user_id=users.id
                                AND telepon.meta_key = 'telepon'

                            LEFT JOIN user_meta AS type_user ON type_user.user_id=users.id
                                AND type_user.meta_key = 'type_user'

                            LEFT JOIN user_meta AS nama_usaha ON nama_usaha.user_id=users.id
                                AND nama_usaha.meta_key = 'nama_usaha'

                            LEFT JOIN user_meta AS jenis_usaha ON jenis_usaha.user_id=users.id
                                AND jenis_usaha.meta_key = 'jenis_usaha'

                            LEFT JOIN user_meta AS lama_berdiri ON lama_berdiri.user_id=users.id
                                AND lama_berdiri.meta_key = 'lama_berdiri'

                            LEFT JOIN user_meta AS omzet ON omzet.user_id=users.id
                                AND omzet.meta_key = 'omzet'

                            LEFT JOIN user_meta AS informasi_usaha ON informasi_usaha.user_id=users.id
                                AND informasi_usaha.meta_key = 'informasi_usaha'

                            LEFT JOIN user_meta AS berusaha_tetap ON berusaha_tetap.user_id=users.id
                                AND berusaha_tetap.meta_key = 'berusaha_tetap'

                            LEFT JOIN user_meta AS dokumen_usaha ON dokumen_usaha.user_id=users.id
                                AND dokumen_usaha.meta_key = 'dokumen_usaha'

                            LEFT JOIN user_meta AS tempat_usaha ON tempat_usaha.user_id=users.id
                                AND tempat_usaha.meta_key = 'tempat_usaha'

                            LEFT JOIN user_meta AS adm_keuangan ON adm_keuangan.user_id=users.id
                                AND adm_keuangan.meta_key = 'adm_keuangan'

                            LEFT JOIN user_meta AS akses_perbankan ON akses_perbankan.user_id=users.id
                                AND akses_perbankan.meta_key = 'akses_perbankan'

                            LEFT JOIN user_meta AS kuisioner_mengapa ON kuisioner_mengapa.user_id=users.id
                                AND kuisioner_mengapa.meta_key = 'kuisioner_mengapa'

                            LEFT JOIN user_meta AS kuisioner_harapan ON kuisioner_harapan.user_id=users.id
                                AND kuisioner_harapan.meta_key = 'kuisioner_harapan'

                            WHERE users.role  NOT IN ( 'admin', 'mentor' )
                            ");

        $users_temp = [];
        array_map(function($user) use(&$users_temp){
            foreach ($user as $k => &$u) {
                if('informasi_usaha'==$k)
                {
                    $info = json_decode($u) ?? [];
                    foreach ($info as $x => $y) {
                        $user->{$x} = $y;   
                    }
                    unset($user->{$k});
                }
            }
            $user = (array) $user;
            $users_temp[] = $user;
        }, $users);
        $users = $users_temp;
        unset($users_temp);
        self::Xport($users);
        return back();

    }

    private static function Xport($users)
    {
        return \Excel::create('users', function($excel) use ($users) {
            $excel->sheet('users', function($sheet) use ($users)
            {
                $sheet->fromArray($users);
            });
        })->download('xls');
    }

}
