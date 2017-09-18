<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;

class DocController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     * @return Response
     */

    // post controller
    public function index(){
    	$meta_title = 'Documentation Ui';
        return view('blog::pages.doc.index')->with(['meta_title' => $meta_title]);
    }
}