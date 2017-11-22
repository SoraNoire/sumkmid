<?php

namespace Modules\Video\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Video\Entities\Video;
use Modules\Video\Entities\VideoCategory;
use Modules\Video\Entities\VideoTag;
use Modules\Video\Entities\VideoCategoryRelation;
use Modules\Video\Entities\VideoTagRelation;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Video\Http\Helpers\VideoHelper;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Image;
use View;

class VideoController extends Controller
{
    private $prefix;

    public function __construct(){
        $this->VideoHelper = new VideoHelper;
        $this->prefix = 'admin/blog/video/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'video');
    }
    /**
     * Display a listing of videos.
     * @return Response
     */
    public function index()
    {
        $page_meta_title = 'Videos';
        return view('video::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single videos.
     * @param  $slug
     * @return Response
     */
    public function show_video($slug){
        $page_meta_title = 'Single Video';
        $video = Video::where('slug', $slug)->first();
        if (isset($video)) {
            $tag = VideoHelper::get_video_tag($video->id);
            $category = VideoHelper::get_video_category($video->id);

            $option = json_decode($video->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $video->status;
            $published_at = $video->published_at;

            return view('video::admin.single')->with(['page_meta_title' => $page_meta_title, 'video' => $video, 'tag' => $tag, 'category' => $category, 'meta_keyword' => $meta_keyword, 'meta_title' => $meta_title, 'meta_desc' => $meta_desc, 'published_at' => $published_at]);
        } else {
            return redirect($this->prefix)->with('msg', 'video Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get videos for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_videos(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'published_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Video::orderBy($col,$direction);
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
     * Show the form for creating a new video.
     * @return Response
     */
    public function create_video()
    {
        $page_meta_title = 'Videos';
        $act = 'New';
        $action = $this->prefix.'store-video';

        $title = '';
        $body = '';
        $selected_tag = '';
        $featured_img = '';
        $media = Media::orderBy('created_at','desc')->get();
        $alltag = VideoTag::orderBy('created_at','desc')->get();
        $video_url = '';
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';

        return view('video::admin.video_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'body' => $body, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'video_url' => $video_url]);
    }

    /**
     * Store a newly created video in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_video(Request $request)
    {
        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $body = $request->input('body');
        $video_url = $request->input('video_url');
        $video_url = str_replace('watch?v=', 'embed/', $video_url);
        $category = $request->get('category');
        $tag = $request->input('tag');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        if ($published_at = 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Video::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::transaction(function() use ($title, $slug, $category, $tag, $body, $featured_img, $option, $status, $published_at, $video_url) {
            if (isset($tag)) {
                // save tag to table tag
                $tag_id = array();
                foreach ($tag as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = VideoTag::where('slug', $tag_slug)->first();
                    if (!isset($check)) {
                        $save_tag = new Tag;
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

            $store = new Video;
            $store->title = $title;
            $store->slug = $slug;
            $store->body = $body;
            $store->video_url = $video_url;
            $store->featured_img = $featured_img;
            $store->featured_img = $featured_img;
            $store->author = 1;
            $store->status = $status;
            $store->option = $option;
            $store->published_at = $published_at;
            $store->save();

            $video_category = new VideoCategoryRelation;
            $video_category->video_id = $store->id;
            $video_category->category_id = json_encode($category);
            $video_category->save();

            $video_tag = new VideoTagRelation;
            $video_tag->video_id = $store->id;
            $video_tag->tag_id = json_encode($tag_id);
            $video_tag->save();
        });
        return redirect($this->prefix)->with(['msg' => 'Saved', 'status' => 'success']);
    }

    /**
     * Show the form for editing video.
     * @param $id
     * @return Response
     */
    public function edit_video($id)
    {
        $page_meta_title = 'Videos';
        $act = 'Edit';
        $action = $this->prefix.'update-video/'.$id;
        $video = Video::where('id', $id)->first();
        if (isset($video)) {
            $title = $video->title;
            $body = $video->body;
            $video_url = $video->video_url;
            $alltag = VideoTag::get();
            $selected_tag = VideoHelper::get_video_category($video->id, 'id');

            $featured_img = $video->featured_img;
            $media = Media::orderBy('created_at','desc')->get();
            $option = json_decode($video->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $video->status;
            $published_at = $video->published_at;
            $item_id = $video->id;

            return view('video::admin.video_form')->with(['item_id' => $item_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'video' => $video , 'title' => $title, 'body' => $body, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'video_url' => $video_url]);
        } else {
            return redirect($this->prefix)->with(['msg' => 'video Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified video in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_video(Request $request, $id)
    {
        $title = $request->input('title');
        $body = $request->input('body');
        $video_url = $request->input('video_url');
        $video_url = str_replace('watch?v=', 'embed/', $video_url);
        $category = $request->get('category');
        $tag = $request->input('tag');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        DB::transaction(function() use ($id, $title, $category, $tag, $body, $featured_img, $option, $status, $published_at, $video_url) {
            if (isset($tag)) {
                // save tag to table tag
                $tag_id = array();
                foreach ($tag as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = VideoTag::where('slug', $tag_slug)->first();
                    if (!isset($check)) {
                        $save_tag = new Tag;
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

            $update = Video::where('id', $id)->first();
            $update->title = $title;
            $update->body = $body;
            $update->video_url = $video_url;
            $update->featured_img = $featured_img;
            $update->author = 1;
            $update->status = $status;
            $update->option = $option;
            $update->published_at = $published_at;
            $update->update();

            $video_category = VideoCategoryRelation::where('video_id', $id)->first();
            $video_category->video_id = $update->id;
            $video_category->category_id = json_encode($category);
            $video_category->update();

            $video_tag = VideoTagRelation::where('video_id', $id)->first();
            $video_tag->video_id = $update->id;
            $video_tag->tag_id = json_encode($tag_id);
            $video_tag->update();
        });
        return redirect($this->prefix.'edit-video/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);

    }

    /**
     * Remove the specified video from storage.
     * @param $id
     * @return Response
     */
    public function destroy_video($id)
    {
        $this->VideoHelper->delete_video($id);
    }

    /**
     * Remove multiple video from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_video(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->VideoHelper->delete_video($id, 'bulk');
        }
        return redirect($this->prefix)->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of category.
     * @return Response
     */
    public function category(){
        $page_meta_title = 'Video Category';
        $category = VideoCategory::get();
        return view('video::admin.category')->with(['page_meta_title' => $page_meta_title, 'category' => $category]);
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
        
        $query = VideoCategory::orderBy($col,$direction);
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
        $page_meta_title = 'Video Category';
        $act = 'New';
        $action = $this->prefix.'store-category';
        $name = ''; 
        $allparent = VideoHelper::get_category_parent();
        return view('video::admin.category_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name, 'allparent' => $allparent]);
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
        $store = new VideoCategory;
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
        $store = new VideoCategory;
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
        $page_meta_title = 'Video Category';
        $act = 'Edit';
        $action = $this->prefix.'update-category/'.$id;
        $category = VideoCategory::where('id', $id)->first();

        if (isset($category)) {
            $maincategory = VideoCategory::where('parent', null)->get(); 
            $allparent = VideoHelper::get_category_parent($category->id);
            $name = $category->name;
            $category_id = $category->id;
            return view('video::admin.category_form')->with(['category_id' => $category_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'category' => $category, 'name' => $name, 'allparent' => $allparent]);
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
        $update = VideoCategory::where('id', $id)->first();
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
        $this->VideoHelper->delete_category($id);
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
            $this->VideoHelper->delete_category($id, 'bulk');
        }
        return redirect($this->prefix.'category')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of tag.
     * @return Response
     */
    public function tag(){
        $page_meta_title = 'Video Tag';
        return view('video::admin.tag')->with(['page_meta_title' => $page_meta_title]);
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
        
        $query = VideoTag::orderBy($col,$direction);
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
        $page_meta_title = 'Video Tag';
        $act = 'New';
        $action = $this->prefix.'store-tag';
        $name = '';
        return view('video::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name]);
    }

    /**
     * Store a newly created tag in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_tag(Request $request){
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new VideoTag;
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
        $page_meta_title = 'Video Tag';
        $act = 'Edit';
        $action = $this->prefix.'update-tag/'.$id;
        $tag = VideoTag::where('id', $id)->first();
        if (isset($tag)) {
            $name = $tag->name;
            return view('video::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'tag' => $tag, 'name' => $name]);
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
        $update = VideoTag::where('id', $id)->first();
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
        $this->VideoHelper->delete_tag($id);
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
            $this->VideoHelper->delete_tag($id, 'bulk');
        }
        return redirect($this->prefix.'tag')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Get all video category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
         return VideoHelper::get_category_parent($category_id);
    }

    /**
     * Get all video category for list on video form.
     * @param  $post_id
     * @return Response
     */
    public static function get_all_category($post_id = ''){
        return VideoHelper::get_all_category($post_id);
    }
}
