<?php

namespace Rabbit\OAuthClient\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rabbit\OAuthClient\Utils\OAuth;
use Rabbit\OAuthClient\Models\Modules;
use Rabbit\OAuthClient\Models\ModulePermissions;

class OAController extends Controller
{
    //

    public function permissions()
    {

    	$modules = Modules::get();
    	$permissions = ModulePermissions::with('module')->get();
    	$p = [];
    	$permissions->map(function(&$pr) use(&$p){
    		$p[$pr->role][$pr->module_id] = (object)$pr;
    	});
    	
    	$data = [
    		'page' => 'Role Management',
    		'roles' => app()->OAuth::$roles,
    		'modules' => $modules,
    		'permissions' => $permissions,
    		'rolepermissions' => $p
    	];

    	// $permissions = Userp::where('user_id',$user->id)->where('app_id',app()->apiClient)->select(['permission_id'])->with('permission')->get();
    	
    	return view('oa::backend.permissions.index',$data);
    }


    public function permissionSave(Request $request)
    {
    	ModulePermissions::truncate();

    	
    	ModulePermissions::insert($r);
    	
    	return redirect(route('OA.permissions'));
    }

    public function permissionSaveAjax(Request $request)
    {
        $val = $request->input('val');
        $checked = ($request->input('check')) ? 1 : 0;
        if (!$val)
        {
            return response('Fail');
        }
        // return $checked;
        $val = explode('-', $val);
        // val[0] = role
        // val[1] = module id
        // val[2] = permission

        if (!isset($val[2]))
        {
            return response('Fail');
        }

        $module = ModulePermissions::where('role',$val[0])->where('module_id',$val[1])->first();

        if ($module) {
            
            $module->{$val[2]} = 0;
            if(1 == $checked)
            {
                $module->{$val[2]} = 1;
            }
            $module->save();

        }
        else
        {
            $m = new ModulePermissions;
            $m->module_id = $val[1];
            $m->role = $val[0];
            if(1 == $checked)
            $m->{$val[2]} = 1;
            $m->save();

        }
        return response('Saved');


    }

    public function modules(Request $request)
    {

    	$refreshRoutes = $request->input('refresh');

    	$modules = Modules::get();

    	$m = [];

    	$modules->map(function($module) use(&$m){
    		$m[$module->name] = (object) ['id'=>$module->id,'readable_name'=>$module->readable_name];
    	});

    	$modules = (object)$m;
    	unset($m);
		// get panel routes 
    	// they are prefixed by "panel."
    	$routes = \Route::getRoutes();

        $wants = [];

        $wantsIgnoreList = [];
        foreach ($routes as $value) {
        	
            if( 'panel' == substr($value->getName(), 0,5) )
            {
            	$moduleName = $value->getName();
                $moduleName = explode('__', $moduleName );
                $moduleName = $moduleName[0];

                if(!in_array($moduleName, $wantsIgnoreList))
                { 
                	if(isset($modules->{$moduleName}))
                	{
                	
                		$wants[] = (object)[
                				'id' => $modules->{$moduleName}->id,
                    			'name' => $moduleName,
                    			'readable_name' => $modules->{$moduleName}->readable_name
                    		];
                	}
                	else
                	{
                		$wants[] = (object)[
                    			'name' => $moduleName,
                    			'readable_name' => ''
                    		];
                	}
                    $wantsIgnoreList[] = $moduleName;
                }
            }
            
        }	




        $modules = (object) $wants;
    	
    	$data = [
    			'page' => 'Module Management',
    			'modules' => $modules
    	];

    	return view('oa::backend.modules.index',$data);
    }

    public function moduleAdd(Request $request)
    {
    	$refreshRoutes = $request->input('refresh');

    	$modules = Modules::get();

    	$m = [];

    	$modules->map(function($module) use(&$m){
    		$m[$module->name] = (object) ['id'=>$module->id,'readable_name'=>$module->readable_name];
    	});

    	$modules = (object)$m;
    	unset($m);
		// get panel routes 
    	// they are prefixed by "panel."
    	$routes = \Route::getRoutes();
        $wants = [];

        foreach ($routes as $value) {
        	
            if( 'panel' == substr($value->getName(), 0,5) )
            {
            	
            	if(isset($modules->{$value->getName()}))
            	{
            	
            		$wants[] = (object)[
            				'id' => $modules->{$value->getName()}->id,
                			'name' => $value->getName(),
                			'readable_name' => $modules->{$value->getName()}->readable_name
                		];
            	}
            	else
            	{
            		$wants[] = (object)[
                			'name' => $value->getName(),
                			'readable_name' => ''
                		];
            	}
            }
            
        }	




        $modules = (object) $wants;

    	
    	$data = [
    			'page' => 'Module Management',
    			'modules' => $modules
    	];

    	return view('oa::backend.modules.add',$data);
    }

    public function moduleSave(Request $request)
    {

    	$sessionMessage = 'Gagal Update modul';
    	$check = $request->input('check');
    	$formModules = $request->input('modulecname');
        foreach ($check as $key => $value) {
            $k = explode('__', $key);
            $k = $k[0];
            if(!isset($check[$k])){
                $check[$k] = $k;
                unset($check[$key]);
            }
        }

    	// update updates
    	if( $check || is_array($check) )
    	{
    		$modules = Modules::all();
    		$updates = [];
    		$modules->map(function(&$m) use($check,$formModules,&$updates){
    			if( isset($check[$m->name]) )
    			{
    				$m->readable_name = $formModules[$m->name]??'';
    				$updates[] = $m->name;
    			}
    			$m->save();
    		});

            // new module each
            $wantsIgnoreList = [];
    		foreach ($check as $key => $value) {
                
                $value = explode('__', $value);
                $value = $value[0];
                

    			if( !in_array($value, $wantsIgnoreList) && !in_array($value, $updates) )
    			{
    				$mod = new Modules;
    				$mod->name = $value;
    				$mod->readable_name = $formModules[$value]??'';
    				$mod->options = '';
    				$mod->save();
                    $wantsIgnoreList[] = $value;
    			}
    		}

    		$sessionMessage = 'Module Updated';
    		// return redirect(route('OA.module.edit'));

    	}

    	// delete deletes
    	$delete = $request->input('delete');
		if( $delete || is_array($delete) )
    	{

    		$sessionMessage = 'Module Updated';
    		Modules::whereIn('id',$request->input('delete'))->delete();
    		// return back();
    	}    	


    	session()->flash('message', $sessionMessage);
    	return redirect(route('OA.modules'));
    }

    public function moduleEdit($id)
    {
    	$module = Modules::where('id',$id)->first();
    	if(!$module)
    	{
    		return redirect(route('OA.modules'));
    	}

    	$data = [
    			'page' => 'Module Management',
    			'module' => $module
    	];
    	return view('oa::backend.modules.edit',$data);
    }
}
