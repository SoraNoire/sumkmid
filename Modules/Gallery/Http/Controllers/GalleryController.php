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
        $this->user = new \App\Helpers\SSOHelper;
        $this->GalleryHelper = new GalleryHelper;
        $this->prefix = 'admin/blog/gallery/';
        View::share('prefix', $this->prefix);
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

        $newdata = array();
        foreach ($output['data'] as $data) {
            $u= $this->user->users($data->author);
            $name = $u->users[0]->username;
            if ($name != '') {
                $data->author_name = $name;
            }
            $newdata[] = $data;
        }
        $output['data'] = $newdata;
        
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
        $alltag = Tags::orderBy('created_at','desc')->get();

        return view('gallery::admin.gallery_add')->with(['page_meta_title' => $page_meta_title, 'alltag' => $alltag]);
    }

    /**
     * Store a newly created gallery in storage.
     * @param  Request $request
     * @return Response
     */
    public function addGalleryPost(Request $request)
    {   
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'gallery_images' => 'required'
        ]);

        $meta_title =  $request->input('meta_title');
        $meta_desc =  $request->input('meta_desc');
        $meta_keyword =  $request->input('meta_keyword');
        $categories = json_encode($request->input('categories') ?? [] );
        $tag_input = $request->input('tags') ?? [];
        $gallery_images = json_encode($request->get('gallery_images') ?? [] );

        $slug = PostHelper::make_slug($request->input('title'));
        if (Posts::where('slug', $slug)->first()) {
            $slug = $slug.'-'.date('s');
        }

        $published_date = $request->input('published_date');
        if ($published_date == 'immediately') {
            $published_date = Carbon::now()->toDateTimeString();
        }

        DB::beginTransaction();
        try {
            if (isset($tag_input)) {
                $tags = array();
                foreach ($tag_input as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = Tags::where('slug', $tag_slug)->first();
                    if (!isset($check)) {
                        // save tag to table tag
                        $save_tag = new Tags;
                        $save_tag->name = $key;
                        $save_tag->slug = $tag_slug;
                        $save_tag->save();
                        $key = $save_tag->id;

                    } else {
                      $key = $check->id;
                    }
                    $tags[] = $key;
                }
            } else {
                $tags = null;
            }
            $tags = json_encode($tags);

            $store = new Posts;
            $store->title = $request->input('title');
            $store->slug = $slug;
            $store->post_type = 'gallery';
            $store->content = $request->input('content');
            $store->featured_image = $request->input('featured_image');
            $store->author = app()->SSO->Auth()->id;
            $store->status = $request->get('status');
            $store->published_date = $published_date;
            $store->save();

            $meta_contents = array();
            $metas[] = ['name' => 'meta_title', 'value' => $meta_title];
            $metas[] = ['name' => 'meta_desc', 'value' => $meta_desc];
            $metas[] = ['name' => 'meta_keyword', 'value' => $meta_keyword];
            $metas[] = ['name' => 'categories', 'value' => $categories];
            $metas[] = ['name' => 'tags', 'value' => $tags];
            $metas[] = ['name' => 'gallery_images', 'value' => $gallery_images];
            foreach ($metas as $meta) {
                if ($meta['value'] != '') {
                    $meta_contents[] = [ 'post_id'=>$store->id, 'key'=> $meta['name'], 'value'=> $meta['value'] ];
                }
            }

            PostMeta::insert($meta_contents);

            DB::commit();
            return redirect(route('galleries'))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB:rollback();
            return redirect(route('galleries'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing gallery.
     * @param $id
     * @return Response
     */
    public function viewGallery($id)
    {
        $page_meta_title = 'Gallery';
        $gallery = Posts::where('id', $id)->first();
        if (isset($gallery)) {

            $post_metas = PostMeta::where('post_id',$gallery->id)->get();            
            $post_metas = $this->readMetas($post_metas);

            $meta_desc = $post_metas->meta_desc ?? '';
            $meta_title = $post_metas->meta_title ?? '';
            $meta_keyword = $post_metas->meta_keyword ?? '';
            $categories = json_decode($post_metas->categories ?? '') ?? [];
            $tags = json_decode($post_metas->tags ?? '') ?? [];
            $gallery_images = json_decode($post_metas->gallery_images ?? '') ?? [];

            $images = Media::whereIn('id', $gallery_images)->get();

            $alltag = Tags::orderBy('created_at','desc')->get();
            $media = Media::orderBy('created_at','desc')->get();

            $title = $gallery->title;
            $content = $gallery->content;
            $item_id = $gallery->id;
            $status = $gallery->status;
            $published_date = $gallery->published_date;
            $featured_image = $gallery->featured_image;

            return view('gallery::admin.gallery_edit')->with(['item_id' => $item_id, 'page_meta_title' => $page_meta_title, 'gallery' => $gallery, 'title' => $title, 'images' => $images, 'alltag' => $alltag, 'selected_tag' => $tags, 'media' => $media, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_date' => $published_date, 'featured_image' => $featured_image, 'content' => $content]);
        } else {
            return redirect(route('galleries'))->with(['msg' => 'gallery Not Found', 'status' => 'danger']);
        }
    }

    function readMetas($arr=[])
    {
        $metas = new \stdClass;;
        foreach ($arr as $key => $value) {
            $metas->{$value->key} = $value->value;
        }
        return $metas;
    }

    /**
     * Update the specified gallery in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updateGallery(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'gallery_images' => 'required'
        ]);

        $title = $request->input('title');
        $content = $request->input('content');
        $tag = $request->input('tag');
        $status = $request->get('status');
        $published_date = $request->input('published_date');
        $featured_img = $request->input('featured_image');
        $tag_input = $request->input('tags') ?? [];

        DB::beginTransaction();
        try {
            if (isset($tag_input)) {
                $tags = array();
                foreach ($tag_input as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = Tags::where('slug', $tag_slug)->first();
                    if (!isset($check)) {
                        // save tag to table tag
                        $save_tag = new Tags;
                        $save_tag->name = $key;
                        $save_tag->slug = $tag_slug;
                        $save_tag->save();
                        $key = $save_tag->id;

                    } else {
                      $key = $check->id;
                    }
                    $tags[] = $key;
                }
            } else {
                $tags = null;
            }
            
            $request->request->add(['tags'=>$tags]);

            $update = Posts::where('id', $id)->first();
            $update->title = $title;
            $update->post_type = 'gallery';
            $update->content = $content;
            $update->featured_image = $featured_img;
            $update->author = app()->SSO->Auth()->id;
            $update->status = $request->get('status');
            $update->published_date = $published_date;
            $update->save();

            $newMeta = false;
            $post_metas = PostMeta::where('post_id',$id)->get();
            $meta_fields = ['categories', 'meta_title', 'meta_desc', 'meta_keyword', 'gallery_images', 'tags' ];

            foreach ($meta_fields as $key => $meta) {
                $updated = false;
                $post_metas->map(function($field) use ($meta,$request,&$updated){
                    if ( $meta == $field->key )
                    {
                        $value = ( 
                                    is_array($request->input($field->key)) ||
                                    is_object($request->input($field->key)) 
                                )
                                ? json_encode($request->input($field->key)) : $request->input($field->key);
                        $field->value = $value ?? $field->value;
                        $field->save();
                        $updated = true;
                        return true;
                    }
                });
                if(!$updated && $request->input($meta))
                {
                    $value = ( 
                                is_array($request->input($meta)) ||
                                is_object($request->input($meta)) 
                            )
                            ? json_encode($request->input($meta)) : $request->input($meta);
                     PostMeta::insert(['post_id'=>$update->id,'key' => $meta, 'value'=>$value]);
                }
            }

            DB::commit();
            return redirect(route('galleries'))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('galleries'))->with(['msg' => 'Error. Something went wrong.', 'status' => 'danger']);
        }     
    }

    /**
     * Remove the specified gallery from storage.
     * @param $id
     * @return Response
     */
    public function removeGallery($id)
    {
        $delete = Posts::find($id);
        if ($delete){
            $delete->deleted = 1;
            $delete->save();
            return redirect(route('galleries'))->with(['msg' => 'Deleted', 'status' => 'success']);
        }
        return redirect(route('galleries'))->with(['msg' => 'Delete error', 'status' => 'danger']);
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
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 1;
                if (!$delete->save()) {
                    return redirect(route('galleries'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('galleries'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'videos']);
            }
        }
        return redirect(route('galleries'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
