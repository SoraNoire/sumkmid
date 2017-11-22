<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cachecreator;
use NewsApi;
use Session;
use App\Helpers\SSOHelper as SSO;

class APIController extends Controller
{

	function __construct(Request $request)
	{
		
	}

	public function events(Request $request)
	{
		$page = $request->input('page') ?? 1;
		$limit = 12;
		$offset = ($page <= 1) ? 0 : ( ($page-1) * $limit);

		$rowSum = \App\Events::count();
		$rows = \App\Events::offset($offset)->limit($limit)->get();
		$pages = (int) ceil($rowSum / $limit);
		
		$data = [
				'page' => $page,
				'of' => $pages,
				'data' => $rows
				];

		return self::_success("Success",$data);
	}

	public function event(Request $request,$id)
	{
		$event = \App\Events::where('id',$id)->first();
		if($event)
		{
			return self::_success("Success",$event);	
		}
		return self::_fail("Event not found");
	}

	public function pages(Request $request)
	{
		$page = $request->input('page') ?? 1;
		$limit = 12;
		$offset = ($page <= 1) ? 0 : ( ($page-1) * $limit);

		$rowSum = \App\Pages::count();
		$rows = \App\Pages::offset($offset)->limit($limit)->get();
		$pages = (int) ceil($rowSum / $limit);
		
		$data = [
				'page' => $page,
				'of' => $pages,
				'data' => $rows
				];

		return self::_success("Success",$data);
	}

	public function page(Request $request,$id)
	{
		$event = \App\Pages::where('id',$id)->first();
		if($event)
		{
			return self::_success("Success",$event);	
		}
		return self::_fail("Page not found");
	}

	public function galleries(Request $request)
	{
		$page = $request->input('page') ?? 1;
		$limit = 12;
		$offset = ($page <= 1) ? 0 : ( ($page-1) * $limit);

		$rowSum = \App\Galleries::count();
		$rows = \App\Galleries::offset($offset)->limit($limit)->get();
		$pages = (int) ceil($rowSum / $limit);
		
		$data = [
				'page' => $page,
				'of' => $pages,
				'data' => $rows
				];

		return self::_success("Success",$data);
	}

	public function gallery(Request $request,$id)
	{
		$event = \App\Galleries::where('id',$id)->first();
		if($event)
		{
			return self::_success("Success",$event);	
		}
		return self::_fail("Galleries not found");
	}

	public function videos(Request $request)
	{
		$page = $request->input('page') ?? 1;
		$limit = 12;
		$offset = ($page <= 1) ? 0 : ( ($page-1) * $limit);

		$rowSum = \App\Videos::count();
		$rows = \App\Videos::offset($offset)->limit($limit)->get();
		$pages = (int) ceil($rowSum / $limit);
		
		$data = [
				'page' => $page,
				'of' => $pages,
				'data' => $rows
				];

		return self::_success("Success",$data);
	}

	public function video(Request $request,$id)
	{
		$event = \App\Videos::where('id',$id)->first();
		if($event)
		{
			return self::_success("Success",$event);	
		}
		return self::_fail("Video not found");
	}













































	private static function _success($message='Success', $data=false)
    {
        $response = [
                        'success'=> true,
                        'message'=> $message
                    ];    
        if ( false != $data )
        {
            $response['data'] = $data;
        }
        
        return response($response);
    }

    private static function _fail($message="Fail")
    {
        $response = [
                        'success'=> false,
                        'message'=> $message
                    ];

        return response($response);
    }
}