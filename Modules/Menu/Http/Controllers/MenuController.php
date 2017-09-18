<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\Page;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Tag;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\PostTag;
use Modules\Blog\Entities\Media;
use Modules\Menu\Entities\Option;
use View;

class MenuController extends Controller
{

    private $prefix;

    public function __construct(){
        $this->prefix = 'admin/blog/';
        View::share('prefix', $this->prefix);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {   
        $meta_title = 'Menu';
        $menu = Option::where('name', 'menu_position')->first();
        $menu_structure = '';
        if (count($menu) > 0) {
            $menu = json_decode($menu->value);
            foreach ($menu as $key) {
                $menu_structure .= '<li class="dd-item" data-id="'.$key->id.'" data-link="'.$key->link.'" data-label="'.$key->label.'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'.$key->id.'"><div class="menu-title"><span>'.$key->label.'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'.$key->id.'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'.$key->id.'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'.$key->label.'"><label>URL</label><input class="form-control" type="url" name="url" value="'.$key->link.'"></div><a href="#" class="remove_item">Remove</a></div></div></li>';
            }
        }
        $pages = Page::orderby('created_at', 'desc')->get();
        $posts = Posts::orderby('created_at', 'desc')->get();
        $category = Category::orderby('created_at', 'desc')->get();
        return view('menu::index')->with(['meta_title' => $meta_title, 'pages' => $pages, 'posts' => $posts, 'category' => $category, 'menu_structure' => $menu_structure]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('menu::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function save_menu(Request $request)
    {
        $option = Option::where('name', 'menu_position')->first();
        $menu_position = $request->menu;
        if (isset($option)) {
            $option -> value = $menu_position;
        } else {
            $option = new Option;
            $option -> name = 'menu_position';
            $option -> value = $menu_position;
        }
        
        if ($option->save()) {
            return 'berhasil simpan';
        } else {
            return 'gagal menyimpan';
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('menu::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('menu::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
