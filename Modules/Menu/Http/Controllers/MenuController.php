<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Menu\Http\Helpers\MenuHelper;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\Option;
use View;

class MenuController extends Controller
{

    private $prefix;

    public function __construct(){
        $this->prefix = 'admin/blog/';
        View::share('prefix', $this->prefix);
    }

    /**
     * Display menu form.
     * @return Response
     */
    public function index()
    {   
        $page_meta_title = 'Menu';
        $meta_title = 'Menu';

        $category = Categories::orderby('created_at', 'desc')->get();
        $list_cat = '';
        if (count($category) > 0){
            $main_category = Categories::where('parent', NULL)->get();
            foreach($main_category as $cat){
                $list_cat .= '<li><label><input type="checkbox" name="menu_category" value="'. $cat->id .'" data-link="/gallery-category/'. $cat->slug .'" data-label="'. $cat->name .'"> '. $cat->name .'</label>';
                $sub_category = Categories::where('parent', $cat->id)->get();
                if (count($sub_category) > 0) {
                    $list_cat .= '<ul>';
                    foreach ($sub_category as $scat) {
                        $list_cat .= '<li><label><input type="checkbox" name="menu_category" value="'. $scat->id .'" data-link="/gallery-category/'. $scat->slug .'" data-label="'. $scat->name .'"> '. $scat->name .'</label></li>';
                    }
                    $list_cat .= '</ul>';
                    $list_cat .= '</li>';
                }
            }
        } else {
            $list_cat .= '<span>No Category Found</span>';
        }

        $pages = Posts::where('post_type', 'page')->orderby('created_at', 'desc')->get();

        return view('menu::index')->with(['meta_title' => $meta_title, 'list_cat' => $list_cat, 'page_meta_title' => $page_meta_title, 'pages' => $pages]);
    }

    /**
     * get list menu (ajax)
     **/
    public function get_menu($menu_type) {
        $name = 'menu_position';
        if ($menu_type == 'top-menu') {
            $name = 'menu_position';
        } elseif ($menu_type == 'footer-menu') {
            $name = 'footer_menu_position';
        }

        $menu = Option::where('key', $name)->first();
        return MenuHelper::getListMenu($menu);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function save_menu(Request $request, $menu_type)
    {
        $name = 'menu_position';
        if ($menu_type == 'top-menu') {
            $name = 'menu_position';
        } elseif ($menu_type == 'footer-menu') {
            $name = 'footer_menu_position';
        } 
        $option = Option::where('key', $name)->first();
        $menu_position = $request->menu;
        if (isset($option)) {
            $option -> value = $menu_position;
        } else {
            $option = new Option;
            $option -> key = $name;
            $option -> value = $menu_position;
        }
        
        if ($option->save()) {
            return 'berhasil simpan';
        } else {
            return 'gagal menyimpan';
        }
    }

    /**
     * Search page.
     * @return Response
     */
    public function search_page_component($search)
    {   
        if ($search == 'none') {
            return '<li>Cannot find anything</li>';
        }

        $result = '';
        $data = Posts::where('post_type', 'page')->where('title', 'like', '%'.$search.'%')->get();
        if (count($data) > 0) {
            foreach ($data as $data) {
                $result .= '<li><label><input type="checkbox" name="menu_page" value="'. $data->id .'" data-link="/'. $data->slug .'" data-label="'. $data->title .'"> '. $data->title .'</label></li>';
            }
            return $result;
        } else {
            return '<li>Cannot find anything</li>';
        }
    }

    /**
     * Search category.
     * @return Response
     */
    public function search_category_component($search)
    {
        if ($search == 'none') {
            return '<li>Cannot find anything</li>';
        }
        
        $result = '';
        $data = Categories::where('name', 'like', '%'.$search.'%')->get();
        if (count($data) > 0) {
            foreach ($data as $data) {
                $result .= '<li><label><input type="checkbox" name="menu_category" value="'. $data->id .'" data-link="'. $data->slug .'" data-label="'. $data->name .'"> '. $data->name .'</label></li>';
            }
            return $result;
        } else {
            return '<li>Cannot find anything</li>';
        }
    }

    /**
     * Display menu form for mobile.
     * @return Response
     */
    public function menu_mobile() {
        $page = 'Menu Mobile';
        $meta_title = 'Menu Mobile';
        $menu = Option::where('key', 'menu_position_mobile')->first();
        $menu_structure = '';
        if (count($menu) > 0) {
            $menu = json_decode($menu->value);
            foreach ($menu as $key) {
                $cat = Categories::where('id', $key)->first();
                if (isset($cat)) {
                    $menu_structure .= '<li class="dd-item" data-id="'.$key.'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default"><div class="menu-title"><span>'.$cat->name.'</span><a href="#" class="remove_item" style="float: right;">Remove</a></div></div></li>';
                }
            }
        }

        $category = Categories::orderby('created_at', 'desc')->where('active', 1)->get();
        $list_cat = '';
        if (count($category) > 0){
            $main_category = Categories::where('parent', NULL)->where('active', 1)->get();
            foreach($main_category as $cat){
                $list_cat .= '<li><label><input type="checkbox" name="menu_category" value="'. $cat->id .'" data-label="'. $cat->name .'"> '. $cat->name .'</label>';
                $sub_category = Categories::where('parent', $cat->id)->where('active', 1)->get();
                if (count($sub_category) > 0) {
                    $list_cat .= '<ul>';
                    foreach ($sub_category as $scat) {
                        $list_cat .= '<li><label><input type="checkbox" name="menu_category" value="'. $scat->id .'" data-link="/gallery-category/'. $scat->slug .'" data-label="'. $scat->name .'"> '. $scat->name .'</label></li>';
                    }
                    $list_cat .= '</ul>';
                    $list_cat .= '</li>';
                }
            }
        } else {
            $list_cat .= '<span>No Category Found</span>';
        }

        return view('menu::mobile-menu')->with(['meta_title' => $meta_title, 'list_cat' => $list_cat, 'menu_structure' => $menu_structure, 'page' => $page]);
    }

    /**
     * Store menu mobile in storage.
     * @param  Request $request
     * @return Response
     */
    public function save_menu_mobile(Request $request)
    {
        $option = Option::where('key', 'menu_position_mobile')->first();
        $menu_position = $request->menu;
        $menus = json_decode($menu_position);
        $new_menu = array();
        foreach ($menus as $menu ) {
            $new_menu[] = $menu->id;
        }
        $menu_position = json_encode($new_menu);
        if (isset($option)) {
            $option -> value = $menu_position;
        } else {
            $option = new Option;
            $option -> key = 'menu_position_mobile';
            $option -> value = $menu_position;
        }
        
        if ($option->save()) {
            return 'berhasil simpan';
        } else {
            return 'gagal menyimpan';
        }
    }
}
