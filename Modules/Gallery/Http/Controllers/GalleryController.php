<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Gallery\Entities\Gallery;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Tags;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Gallery\Http\Helpers\GalleryHelper;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Image;
use View;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;

class GalleryController extends Controller
{
    private $prefix;

    public function __construct(){
        $this->GalleryHelper = new GalleryHelper;
        $this->prefix = 'admin/blog/gallery/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'gallery');
    }
    /**
     * Display a listing of gallery.
     * @return Response
     */
    public function index()
    {
        $page_meta_title = 'Gallery';
        return view('gallery::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single gallery.
     * @param  $slug
     * @return Response
     */
    public function showGallery($slug){
        $page_meta_title = 'Gallery';
        $gallery = Posts::where('slug', $slug)->first();
        if (isset($gallery)) {
            $tag = GalleryHelper::get_gallery_tag($gallery->id);
            $category = GalleryHelper::get_gallery_category($gallery->id);
            $option = json_decode($gallery->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $gallery->status;
            $published_at = $gallery->published_at;

            return view('gallery::admin.single')->with(['page_meta_title' => $page_meta_title, 'gallery' => $gallery, 'tag' => $tag, 'category' => $category, 'meta_keyword' => $meta_keyword, 'meta_title' => $meta_title, 'meta_desc' => $meta_desc, 'published_at' => $published_at]);
        } else {
            return redirect($this->prefix)->with('msg', 'gallery Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get gallery for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxGalleries(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'published_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('post_type','gallery')->where('deleted',0)->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();
        $output['recordsTotal'] = $query->count();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = 10;

        return $output;
    }

    /**
     * Show the form for creating a new gallery.
     * @return Response
     */
    public function addGallery()
    {
        $page_meta_title = 'Gallery';
        $act = 'New';
        $action = $this->prefix.'store-gallery';

        $title = '';
        $selected_tag = '';
        $media = Media::orderBy('created_at','desc')->get();
        $alltag = Tags::orderBy('created_at','desc')->get();
        $allcategory = Categories::orderBy('created_at','desc')->get();
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_date = 'immediately';
        $featured_image = '';

        return view('gallery::admin.gallery_form')->with(['isEdit'=>false, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'allcategory' => $allcategory, 'media' => $media, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_date' => $published_date, 'featured_image' => $featured_image]);
    }

    /**
     * Store a newly created gallery in storage.
     * @param  Request $request
     * @return Response
     */
    public function addGalleryPost(Request $request)
    {

        /* ======================================================================== */


        DB::transaction(function() use ($request) {
        
            $slug = PostHelper::make_slug($request->input('title'));
            if (Posts::where('slug', $slug)->first()) {
                $slug = $slug.'-'.date('s');
            }

            $published_date = $request->input('published_date');
            if ($published_date = 'immediately') {
                $published_date = Carbon::now()->toDateTimeString();
            }

            $store = new Posts;
            $store->title = $request->input('title');
            $store->slug = $slug;
            $store->post_type = 'gallery';
            $store->content = $request->input('title');
            $store->featured_image = $request->input('featured_image');
            $store->author = app()->SSO->Auth()->id;
            $store->status = $request->get('status');
            $store->published_date = $published_date;
            if ($store->save()) {

                $meta_contents = [
                                    ['post_id'=>$store->id, 'key'=> 'meta_title', 'value'=> $request->input('meta_title')],
                                    ['post_id'=>$store->id, 'key'=> 'meta_desc', 'value'=> $request->input('meta_desc')],
                                    ['post_id'=>$store->id, 'key'=> 'meta_keyword', 'value'=> $request->input('meta_keyword')],
                                    ['post_id'=>$store->id, 'key'=> 'categories', 'value'=> json_encode($request->input('categories') ?? [] )],
                                    ['post_id'=>$store->id, 'key'=> 'tags', 'value'=> json_encode($request->input('tags') ?? [] )],
                                    ['post_id'=>$store->id, 'key'=> 'gallery_images', 'value'=> json_encode($request->input('gallery_images') ?? [] )],
                                ];
                PostMeta::insert($meta_contents);

                return redirect(route('galleries'))->with(['msg' => 'Saved', 'status' => 'success']);
            } else {
                return redirect(route('galleries'))->with(['msg' => 'Save Error', 'status' => 'danger']);
            }


        });
        return redirect(route('galleries'))->with(['msg' => 'Saved', 'status' => 'success']);

        /* ======================================================================== */
    }

    /**
     * Show the form for editing gallery.
     * @param $id
     * @return Response
     */
    public function viewGallery($id)
    {
        $page_meta_title = 'Gallery';
        $act = 'Edit';
        $action = $this->prefix.'update-gallery/'.$id;
        $gallery = Posts::where('id', $id)->first();
        if (isset($gallery)) {
            $title = $gallery->title;
            $ids = explode(',', $gallery->images);
            $images = Media::whereIn('id', $ids)->get();

            $alltag = GalleryTag::get();
            $selected_tag = GalleryHelper::get_gallery_tag($gallery->id, 'id');

            $media = Media::orderBy('created_at','desc')->get();

            $option = json_decode($gallery->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $gallery->status;
            $published_at = $gallery->published_at;
            $featured_img = $gallery->featured_img;

            return view('gallery::admin.gallery_form')->with(['item_id' => $id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'gallery' => $gallery , 'title' => $title, 'images' => $images, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'media' => $media, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'featured_img' => $featured_img]);
        } else {
            return redirect($this->prefix)->with(['msg' => 'gallery Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified gallery in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updateGallery(Request $request, $id)
    {
        $title = $request->input('title');
        $images = $request->input('selected_image');
        $category = $request->get('category');
        $tag = $request->input('tag');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $featured_img = $request->input('featured_img');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        if (empty($images)) {
            return redirect($this->prefix)->with(['msg' => 'Error saving. There is no image selected', 'status' => 'danger']);
        }

        if (is_array($images)) {
            $images = implode(",", $request->get('selected_image'));
        } else {
            $images = $request->input('selected_image');
        }

        DB::beginTransaction();
        try {
            if (isset($tag)) {
                // save tag to table tag
                $tag_id = array();
                foreach ($tag as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = GalleryTag::where('slug', $tag_slug)->first();
                    if (!isset($check)) {
                        $save_tag = new GalleryTag;
                        $save_tag->name = $key;
                        $save_tag->slug = $tag_slug;
                        $save_tag->save();
                        $key = $save_tag->id;

                    } else {
                      $key = $check->id;
                    }
                    $tag_id[] = $key;
                }
            } else {
                $tag_id = null;
            }

            $update = Posts::where('id', $id)->first();
            $update->title = $title;
            $update->images = $images;
            $update->featured_img = $featured_img;
            $update->author = 1;
            $update->status = $status;
            $update->option = $option;
            $update->published_at = $published_at;
            $update->update();

            $gallery_category = GalleryCategoryRelation::where('gallery_id', $id)->first();
            $gallery_category->gallery_id = $update->id;
            $gallery_category->category_id = json_encode($category);
            $gallery_category->update();

            $gallery_tag = GalleryTagRelation::where('gallery_id', $id)->first();
            $gallery_tag->gallery_id = $update->id;
            $gallery_tag->tag_id = json_encode($tag_id);
            $gallery_tag->update();

            DB::commit();
            return redirect($this->prefix.'edit-gallery/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            DB::rollBack();
            return redirect($this->prefix.'edit-gallery/'.$id)->with(['msg' => 'Error. Something went wrong.', 'status' => 'danger']);
        }     
    }

    /**
     * Remove the specified gallery from storage.
     * @param $id
     * @return Response
     */
    public function removeGallery($id)
    {
        $this->GalleryHelper->delete_gallery($id);
    }

    /**
     * Remove multiple gallery from storage.
     * @param Request $request
     * @return Response
     */
    public function massdeleteGallery(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->GalleryHelper->delete_gallery($id, 'bulk');
        }
        return redirect($this->prefix)->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of category.
     * @return Response
     */
    public function category(){
        $page_meta_title = 'Gallery Category';
        $category = GalleryCategory::get();
        return view('gallery::admin.category')->with(['page_meta_title' => $page_meta_title, 'category' => $category]);
    }

    /**
     * Get categories for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_category(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = GalleryCategory::orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('name', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();
        $output['recordsTotal'] = $query->count();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = 10;

        return $output;
    }

    /**
     * Show the form for creating a new category.
     * @return Response
     */
    public function create_category(){
        $page_meta_title = 'Gallery Category';
        $act = 'New';
        $action = $this->prefix.'store-category';
        $name = ''; 
        $allparent = GalleryHelper::get_category_parent();
        return view('gallery::admin.category_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name, 'allparent' => $allparent]);
    }

    /**
     * Store a newly created category in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_category(Request $request){
        $parent = $request->get('parent');
        if ($parent == 'none') {
            $parent = null;
        }
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new GalleryCategory;
        $store->name = $request->input('name');
        $store->slug = $slug;
        $store->parent = $parent;
        if ($store->save()){
            return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Store a newly created category in storage. Ajax function.
     * @param  $name, $parent
     * @return Response
     */
    public function store_category_ajax($name, $parent){
        if ($parent == 'none') {
            $parent = null;
        }
        $slug = PostHelper::make_slug($name);
        $store = new GalleryCategory;
        $store->name = $name;
        $store->slug = $slug;
        $store->parent = $parent;
        if ($store->save()){
            return 'success saving category';
        } else {
            return 'error saving category';
        }
    }

    /**
     * Show the form for editing the specified category.
     * @param $id
     * @return Response
     */
    public function edit_category($id){
        $page_meta_title = 'Gallery Category';
        $act = 'Edit';
        $action = $this->prefix.'update-category/'.$id;
        $category = GalleryCategory::where('id', $id)->first();

        if (isset($category)) {
            $maincategory = GalleryCategory::where('parent', null)->get(); 
            $allparent = GalleryHelper::get_category_parent($category->id);
            $name = $category->name;
            return view('gallery::admin.category_form')->with(['category_id' => $id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'category' => $category, 'name' => $name, 'allparent' => $allparent]);
        }else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified category in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_category(Request $request, $id){
        $parent = $request->get('parent');
        if ($parent == 'none') {
            $parent = null;
        }
        $update = GalleryCategory::where('id', $id)->first();
        $update->name = $request->input('name');
        $update->parent = $parent;
        if ($update->save()){
            return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect($this->prefix.'category');
    }

    /**
     * Remove the specified category from storage.
     * @param $id
     * @return Response
     */
    public function destroy_category($id){
        $this->GalleryHelper->delete_category($id);
    }

    /**
     * Remove multiple category from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_category(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->GalleryHelper->delete_category($id, 'bulk');
        }
        return redirect($this->prefix.'category')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of tag.
     * @return Response
     */
    public function tag(){
        $page_meta_title = 'Gallery Tag';
        return view('gallery::admin.tag')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Get tags for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_tag(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = GalleryTag::orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('name', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();
        $output['recordsTotal'] = $query->count();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = 10;

        return $output;
    }

    /**
     * Show the form for creating a new tag.
     * @return Response
     */
    public function create_tag(){
        $page_meta_title = 'Gallery Tag';
        $act = 'New';
        $action = $this->prefix.'store-tag';
        $name = '';
        return view('gallery::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name]);
    }

    /**
     * Store a newly created tag in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_tag(Request $request){
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new GalleryTag;
        $store->name = $request->input('name');
        $store->slug = $slug;
        if ($store->save()){
            return redirect($this->prefix.'tag')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'tag')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing the specified tag.
     * @param $id
     * @return Response
     */
    public function edit_tag($id){
        $page_meta_title = 'Gallery Tag';
        $act = 'Edit';
        $action = $this->prefix.'update-tag/'.$id;
        $tag = GalleryTag::where('id', $id)->first();
        if (isset($tag)) {
            $name = $tag->name;
            return view('gallery::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'tag' => $tag, 'name' => $name]);
        } else {
            return redirect($this->prefix.'tag')->with('msg', 'Tag Not Found')->with('status', 'danger');
        }
    }

    /**
     * Update the specified tag in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_tag(Request $request, $id){
        $update = GalleryTag::where('id', $id)->first();
        $update->name = $request->input('name');
        if ($update->save()){
            return redirect($this->prefix.'tag')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'tag')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Remove the specified tag from storage.
     * @param $id
     * @return Response
     */
    public function destroy_tag($id){
        $this->GalleryHelper->delete_tag($id);
    }

    /**
     * Remove multiple tag from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_tag(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->GalleryHelper->delete_tag($id, 'bulk');
        }
        return redirect($this->prefix.'tag')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Get all gallery category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
        return GalleryHelper::get_category_parent($category_id);
    }

    /**
     * Get all gallery category for list on gallery form.
     * @param  $post_id
     * @return Response
     */
    public static function get_all_category($gallery_id = ''){
        return GalleryHelper::get_all_category($gallery_id);
    }
}
