<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Gallery\Entities\Gallery;
use Modules\Gallery\Entities\GalleryCategory;
use Modules\Gallery\Entities\GalleryTag;
use Modules\Gallery\Entities\GalleryCategoryRelation;
use Modules\Gallery\Entities\GalleryTagRelation;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Gallery\Http\Helpers\GalleryHelper;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Image;
use View;

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
    public function show_gallery($slug){
        $page_meta_title = 'Gallery';
        $gallery = Gallery::where('slug', $slug)->first();
        if (isset($gallery)) {
            $galleryTag = json_decode(GalleryTagRelation::where('gallery_id', $gallery->id)->first()->tag_id);
            $tag = array();
            if (count($tag) > 0) {
                foreach ($galleryTag as $galleryTag) {
                    $tag[] = GalleryTag::where('id', $galleryTag)->first();
                }
            }

            $galleryCategory = json_decode(GalleryCategoryRelation::where('gallery_id', $gallery->id)->first()->category_id);
            $category = array();
            if (count($category) > 0) {
                foreach ($galleryCategory as $galleryCategory) {
                    $category[] = GalleryCategory::where('id', $galleryCategory)->first();
                }
            }

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
    public function get_gallery(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'published_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Gallery::orderBy($col,$direction);
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
    public function create_gallery()
    {
        $page_meta_title = 'Gallery';
        $act = 'New';
        $action = $this->prefix.'store-gallery';

        $title = '';
        $selected_tag = '';
        $media = Media::orderBy('created_at','desc')->get();
        $alltag = GalleryTag::orderBy('created_at','desc')->get();
        $allcategory = GalleryHelper::get_all_category();
        $allparent = GalleryHelper::get_category_parent();
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';

        return view('gallery::admin.gallery_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'allcategory' => $allcategory, 'media' => $media, 'allparent' => $allparent, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at]);
    }

    /**
     * Store a newly created gallery in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_gallery(Request $request)
    {
        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $images = $request->input('selected_image');
        $category = $request->get('category');
        $tag = $request->input('tag');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        if (empty($images)) {
            return redirect($this->prefix.'create-gallery')->with(['msg' => 'Error saving. There is no image selected', 'status' => 'danger']);
        }

        if (is_array($images)) {
            $images = implode(",", $request->get('selected_image'));
        } else {
            $images = $request->input('selected_image');
        }

        if ($published_at = 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Gallery::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
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

            $store = new Gallery;
            $store->title = $title;
            $store->slug = $slug;
            $store->images = $images;
            $store->author = 1;
            $store->status = $status;
            $store->option = $option;
            $store->published_at = $published_at;
            $store->save();

            $gallery_category = new GalleryCategoryRelation;
            $gallery_category->gallery_id = $store->id;
            $gallery_category->category_id = json_encode($category);
            $gallery_category->save();

            $gallery_tag = new GalleryTagRelation;
            $gallery_tag->gallery_id = $store->id;
            $gallery_tag->tag_id = json_encode($tag_id);
            $gallery_tag->save();

            DB::commit();

            return redirect($this->prefix)->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            DB::rollBack();

            return redirect($this->prefix)->with(['msg' => 'Error saving. Something went wrong', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing gallery.
     * @param $id
     * @return Response
     */
    public function edit_gallery($id)
    {
        $page_meta_title = 'Gallery';
        $act = 'Edit';
        $action = $this->prefix.'update-gallery/'.$id;
        $gallery = Gallery::where('id', $id)->first();
        if (isset($gallery)) {
            $title = $gallery->title;
            $ids = explode(',', $gallery->images);
            $images = Media::whereIn('id', $ids)->get();

            $alltag = GalleryTag::get();
            $galleryTag = GalleryTagRelation::where('gallery_id', $id)->first();
            $selected_tag_id = json_decode($galleryTag->tag_id);
            $selected_tag = array();
            if (count($selected_tag_id) > 0) {
                foreach ($selected_tag_id as $key) {
                    $tag = GalleryTag::where('id', $key)->first()->id;
                    $selected_tag[] = $tag;
                }
            }

            $media = Media::orderBy('created_at','desc')->get();

            $allcategory = GalleryHelper::get_all_category($gallery->id);
            $allparent = GalleryHelper::get_category_parent();
            $option = json_decode($gallery->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $gallery->status;
            $published_at = $gallery->published_at;

            return view('gallery::admin.gallery_form')->with(['item_id' => $id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'gallery' => $gallery , 'title' => $title, 'images' => $images, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'allcategory' => $allcategory, 'media' => $media, 'allparent' => $allparent, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at]);
        } else {
            return redirect($this->prefix)->with(['msg' => 'gallery Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified gallery in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_gallery(Request $request, $id)
    {
        $title = $request->input('title');
        $images = $request->input('selected_image');
        $category = $request->get('category');
        $tag = $request->input('tag');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
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

            $update = Gallery::where('id', $id)->first();
            $update->title = $title;
            $update->images = $images;
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
    public function destroy_gallery($id)
    {
        $this->GalleryHelper->delete_gallery($id);
    }

    /**
     * Remove multiple gallery from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_gallery(Request $request)
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
