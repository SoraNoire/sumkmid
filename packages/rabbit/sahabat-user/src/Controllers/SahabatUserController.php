<?php

namespace Rabbit\SahabatUser\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    function __construct(Request $request)
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

    private static function listUsaha()
    {
        $usaha = [
                    'Aplikasi Dan Pengembang Permainan',
                    'Arsitektur',
                    'Desain Interior',
                    'Desain Komunikasi Visual',
                    'Desain Produk',
                    'Fashion',
                    'Film, Animasi, Dan Video',
                    'Fotografi',
                    'Kriya',
                    'Kuliner',
                    'Musik',
                    'Penerbitan',
                    'Periklanan',
                    'Seni Pertunjukan',
                    'Seni Rupa',
                    'Televisi Dan Radio'
            ];
        return (object)$usaha;
    }

    private static function meta_user(){
        $userMeta = UserMeta::where('user_id',app()->OAuth::Auth()->id)->get();
        $userData = [];
        $user = app()->OAuth::$Auth;
        $userMeta->map(
            function($meta) use(&$userData,&$user){
                if( 'type_user' == $meta->meta_key )
                {
                    $user->role = $meta->meta_value;
                }
                else{
                    // $user->{$meta->meta_key} = $meta->meta_value;
                    $userData[$meta->meta_key] = $meta->meta_value;
                }
            }
        );
        app()->OAuth::$Auth->data = (object)$userData;
        unset($user);
    }

    private static function check_steps()
    {
        $user = app()->OAuth::Auth();
        // dd($user->data->kota_lahir);
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
                    && isset($user->data->lama_berdiri) && isset($user->data->informasi_usaha) && isset($user->data->omzet)
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
                    'usaha' => self::listUsaha(),
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

    public function completionSave(Request $request)
    {


        // save 'kota lahir'
        if($request->input('kota_lahir'))
        {
            self::add_or_update_meta('kota_lahir',$request->input('kota_lahir'));
        }

        // save 'tahun lahir'
        if($request->input('tahun_lahir'))
        {
            $tahunLahir = $request->input('tahun_lahir')
                            .'/'.$request->input('bulan_lahir')
                            .'/'.$request->input('tanggal_lahir');

            self::add_or_update_meta('tanggal_lahir',$tahunLahir);
        }

        // save 'alamat'
        if($request->input('alamat'))
        {
            self::add_or_update_meta('alamat',$request->input('alamat'));
        }

        // save 'provinsi'
        if($request->input('provinsi'))
        {
            self::add_or_update_meta('provinsi',$request->input('provinsi'));
        }

        // save 'kota'
        if($request->input('kota'))
        {
            self::add_or_update_meta('kota',$request->input('kota'));
        }

        // save 'telepon'
        if($request->input('telepon'))
        {
            self::add_or_update_meta('telepon',$request->input('telepon'));
        }

        // save 'type_user'
        if($request->input('type_user'))
        {
            $typeUser = ( 'ya' == $request->input('type_user') ) ? 'umkm' : 'perorangan';
            self::add_or_update_meta('type_user',$typeUser);
            // delete umkm data if any
            UserMeta::whereIn('meta_key',['nama_usaha','jenis_usaha','lama_berdiri','omzet'])
                      ->where('user_id',app()->OAuth::Auth()->id)->delete();
        }

        // save 'nama_usaha'
        if($request->input('nama_usaha'))
        {
            self::add_or_update_meta('nama_usaha',$request->input('nama_usaha'));
        }

        // save 'jenis_usaha'
        if($request->input('jenis_usaha'))
        {
            self::add_or_update_meta('jenis_usaha',$request->input('jenis_usaha'));
        }

        // save 'lama_berdiri'
        if($request->input('lama_berdiri'))
        {
            self::add_or_update_meta('lama_berdiri',$request->input('lama_berdiri'));
        }

        // save 'omzet'
        if($request->input('omzet'))
        {
            if( 'null' != $request->input('omzet') )
            {
              self::add_or_update_meta('omzet',$request->input('omzet'));
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

            self::add_or_update_meta('foto_ktp',$filename);
        }

        // save 'informasi_usaha'
        if($request->input('informasi_usaha'))
        {

            if ( 1 == sizeof($request->input('info')) )
            {
              foreach ($request->input('info') as $key => $i) {
                  if($i && '' != $i){
                    self::add_or_update_meta('informasi_usaha',json_encode($request->input('info')));
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
              self::add_or_update_meta('informasi_usaha',json_encode($info));
            }
        }


        // save 'usaha_tetap'
        if($request->input('usaha_tetap'))
        {

            self::add_or_update_meta('usaha_tetap',$request->input('usaha_tetap'));
        }
        // save 'kelengkapan_dokumen'
        if($request->input('kelengkapan_dokumen'))
        {

            self::add_or_update_meta('kelengkapan_dokumen',$request->input('kelengkapan_dokumen'));
        }
        // save 'tempat_usaha'
        if($request->input('tempat_usaha'))
        {

            self::add_or_update_meta('tempat_usaha',$request->input('tempat_usaha'));
        }
        // save 'adm_keuangan'
        if($request->input('adm_keuangan'))
        {

            self::add_or_update_meta('adm_keuangan',$request->input('adm_keuangan'));
        }
        // save 'akses_perbankan'
        if($request->input('akses_perbankan'))
        {

            self::add_or_update_meta('akses_perbankan',$request->input('akses_perbankan'));
        }



        // save 'kuisioner_mengapa'
        if($request->input('kuisioner_mengapa'))
        {

            self::add_or_update_meta('kuisioner_mengapa',$request->input('kuisioner_mengapa'));
        }

        // save 'kuisionar_harapan'
        if($request->input('kuisioner_harapan'))
        {

            self::add_or_update_meta('kuisioner_harapan',$request->input('kuisioner_harapan'));
        }

        // save 'tos_terima'
        if($request->input('tos_terima'))
        {

            $tos = ( 'on' == $request->input('tos_terima') ) ? 1 : 0;
            if( $request->input('kuisioner_harapan') && $request->input('kuisioner_mengapa')  )
            {
              self::add_or_update_meta('tos_terima',$tos);
            }
        }


        
        return redirect('/')->send();
    }


    private static function add_or_update_meta($key,$val)
    {
        if(UserMeta::where('user_id',app()->OAuth::Auth()->id)->where('meta_key',$key)->first())
        {
            UserMeta::where('user_id',app()->OAuth::Auth()->id)
                    ->where('meta_key',$key)
                    ->update(['meta_value'=>$val]);
        }
        else
        {
            $insert = [
                        'user_id' => app()->OAuth::Auth()->id,
                        'meta_key' => $key,
                        'meta_value' => $val
                    ];
            UserMeta::insert($insert);
        }
    }
    //

  //   public function permissions()
  //   {

  //   	$modules = Modules::get();
  //   	$permissions = ModulePermissions::with('module')->get();
  //   	$p = [];
  //   	$permissions->map(function(&$pr) use(&$p){
  //   		$p[$pr->role][$pr->module_id] = (object)$pr;
  //   	});
    	
  //   	$data = [
  //   		'page' => 'Role Management',
  //   		'roles' => app()->OAuth::$roles,
  //   		'modules' => $modules,
  //   		'permissions' => $permissions,
  //   		'rolepermissions' => $p
  //   	];

  //   	// $permissions = Userp::where('user_id',$user->id)->where('app_id',app()->apiClient)->select(['permission_id'])->with('permission')->get();
    	
  //   	return view('oa::backend.permissions.index',$data);
  //   }


  //   public function permissionSave(Request $request)
  //   {
  //   	ModulePermissions::truncate();

    	
  //   	ModulePermissions::insert($r);
    	
  //   	return redirect(route('OA.permissions'));
  //   }

  //   public function permissionSaveAjax(Request $request)
  //   {
  //       $val = $request->input('val');
  //       $checked = ($request->input('check')) ? 1 : 0;
  //       if (!$val)
  //       {
  //           return response('Fail');
  //       }
  //       // return $checked;
  //       $val = explode('-', $val);
  //       // val[0] = role
  //       // val[1] = module id
  //       // val[2] = permission

  //       if (!isset($val[2]))
  //       {
  //           return response('Fail');
  //       }

  //       $module = ModulePermissions::where('role',$val[0])->where('module_id',$val[1])->first();

  //       if ($module) {
            
  //           $module->{$val[2]} = 0;
  //           if(1 == $checked)
  //           {
  //               $module->{$val[2]} = 1;
  //           }
  //           $module->save();

  //       }
  //       else
  //       {
  //           $m = new ModulePermissions;
  //           $m->module_id = $val[1];
  //           $m->role = $val[0];
  //           if(1 == $checked)
  //           $m->{$val[2]} = 1;
  //           $m->save();

  //       }
  //       return response('Saved');


  //   }

  //   public function modules(Request $request)
  //   {

  //   	$refreshRoutes = $request->input('refresh');

  //   	$modules = Modules::get();

  //   	$m = [];

  //   	$modules->map(function($module) use(&$m){
  //   		$m[$module->name] = (object) ['id'=>$module->id,'readable_name'=>$module->readable_name];
  //   	});

  //   	$modules = (object)$m;
  //   	unset($m);
		// // get panel routes 
  //   	// they are prefixed by "panel."
  //   	$routes = \Route::getRoutes();

  //       $wants = [];

  //       $wantsIgnoreList = [];
  //       foreach ($routes as $value) {
        	
  //           if( 'panel' == substr($value->getName(), 0,5) )
  //           {
  //           	$moduleName = $value->getName();
  //               $moduleName = explode('__', $moduleName );
  //               $moduleName = $moduleName[0];

  //               if(!in_array($moduleName, $wantsIgnoreList))
  //               { 
  //               	if(isset($modules->{$moduleName}))
  //               	{
                	
  //               		$wants[] = (object)[
  //               				'id' => $modules->{$moduleName}->id,
  //                   			'name' => $moduleName,
  //                   			'readable_name' => $modules->{$moduleName}->readable_name
  //                   		];
  //               	}
  //               	else
  //               	{
  //               		$wants[] = (object)[
  //                   			'name' => $moduleName,
  //                   			'readable_name' => ''
  //                   		];
  //               	}
  //                   $wantsIgnoreList[] = $moduleName;
  //               }
  //           }
            
  //       }	




  //       $modules = (object) $wants;
    	
  //   	$data = [
  //   			'page' => 'Module Management',
  //   			'modules' => $modules
  //   	];

  //   	return view('oa::backend.modules.index',$data);
  //   }

  //   public function moduleAdd(Request $request)
  //   {
  //   	$refreshRoutes = $request->input('refresh');

  //   	$modules = Modules::get();

  //   	$m = [];

  //   	$modules->map(function($module) use(&$m){
  //   		$m[$module->name] = (object) ['id'=>$module->id,'readable_name'=>$module->readable_name];
  //   	});

  //   	$modules = (object)$m;
  //   	unset($m);
		// // get panel routes 
  //   	// they are prefixed by "panel."
  //   	$routes = \Route::getRoutes();
  //       $wants = [];

  //       foreach ($routes as $value) {
        	
  //           if( 'panel' == substr($value->getName(), 0,5) )
  //           {
            	
  //           	if(isset($modules->{$value->getName()}))
  //           	{
            	
  //           		$wants[] = (object)[
  //           				'id' => $modules->{$value->getName()}->id,
  //               			'name' => $value->getName(),
  //               			'readable_name' => $modules->{$value->getName()}->readable_name
  //               		];
  //           	}
  //           	else
  //           	{
  //           		$wants[] = (object)[
  //               			'name' => $value->getName(),
  //               			'readable_name' => ''
  //               		];
  //           	}
  //           }
            
  //       }	




  //       $modules = (object) $wants;

    	
  //   	$data = [
  //   			'page' => 'Module Management',
  //   			'modules' => $modules
  //   	];

  //   	return view('oa::backend.modules.add',$data);
  //   }

  //   public function moduleSave(Request $request)
  //   {

  //   	$sessionMessage = 'Gagal Update modul';
  //   	$check = $request->input('check');
  //   	$formModules = $request->input('modulecname');
  //       foreach ($check as $key => $value) {
  //           $k = explode('__', $key);
  //           $k = $k[0];
  //           if(!isset($check[$k])){
  //               $check[$k] = $k;
  //               unset($check[$key]);
  //           }
  //       }

  //   	// update updates
  //   	if( $check || is_array($check) )
  //   	{
  //   		$modules = Modules::all();
  //   		$updates = [];
  //   		$modules->map(function(&$m) use($check,$formModules,&$updates){
  //   			if( isset($check[$m->name]) )
  //   			{
  //   				$m->readable_name = $formModules[$m->name]??'';
  //   				$updates[] = $m->name;
  //   			}
  //   			$m->save();
  //   		});

  //           // new module each
  //           $wantsIgnoreList = [];
  //   		foreach ($check as $key => $value) {
                
  //               $value = explode('__', $value);
  //               $value = $value[0];
                

  //   			if( !in_array($value, $wantsIgnoreList) && !in_array($value, $updates) )
  //   			{
  //   				$mod = new Modules;
  //   				$mod->name = $value;
  //   				$mod->readable_name = $formModules[$value]??'';
  //   				$mod->options = '';
  //   				$mod->save();
  //                   $wantsIgnoreList[] = $value;
  //   			}
  //   		}

  //   		$sessionMessage = 'Module Updated';
  //   		// return redirect(route('OA.module.edit'));

  //   	}

  //   	// delete deletes
  //   	$delete = $request->input('delete');
		// if( $delete || is_array($delete) )
  //   	{

  //   		$sessionMessage = 'Module Updated';
  //   		Modules::whereIn('id',$request->input('delete'))->delete();
  //   		// return back();
  //   	}    	


  //   	session()->flash('message', $sessionMessage);
  //   	return redirect(route('OA.modules'));
  //   }

  //   public function moduleEdit($id)
  //   {
  //   	$module = Modules::where('id',$id)->first();
  //   	if(!$module)
  //   	{
  //   		return redirect(route('OA.modules'));
  //   	}

  //   	$data = [
  //   			'page' => 'Module Management',
  //   			'module' => $module
  //   	];
  //   	return view('oa::backend.modules.edit',$data);
  //   }
}
