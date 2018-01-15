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

use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;
use Modules\Blog\Entities\Tags;

class VideoController extends Controller
{
    private $prefix;

    public function __construct(){
        $this->VideoHelper = new VideoHelper;
        $this->prefix = 'admin/blog/video/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'video');
        View::share('tinymceApiKey', config('app.tinymce_api_key'));
    }
    /**
     * Display a listing of videos.
     * @return Response
     */
    public function index()
    {
        $page_meta_title = 'Gallery';
        return view('video::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single videos.
     * @param  $slug
     * @return Response
     */
    public function show_video($slug){
        $page_meta_title = 'Single Video';
        $video = Posts::where('slug', $slug)->first();
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
            return redirect(route('panel.gallery__index'))->with('msg', 'video Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get videos for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxVideos(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'published_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('post_type','video')->where('deleted','0')->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->offset($request['start'])->limit($request['length'])->get();

        $newdata = array();
        foreach ($output['data'] as $data) {
            $u= app()->OAuth->users($data->author);
            $name = $u->username ?? 'admin';
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
     * Show the form for creating a new video.
     * @return Response
     */
    public function addVideo()
    {
        $page_meta_title = 'Gallery';
        $alltag = Tags::orderBy('created_at','desc')->get();

        return view('video::admin.video_add')->with(['page_meta_title' => $page_meta_title, 'alltag' => $alltag]);
    }

    /**
     * Store a newly created video in storage.
     * @param  Request $request
     * @return Response
     */
    public function addVideoPost(Request $request)
    {   
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ], PostHelper::validation_messages());

        $meta_title =  $request->input('meta_title');
        $meta_desc =  $request->input('meta_desc');
        $meta_keyword =  $request->input('meta_keyword');
        $video_url = str_replace('watch?v=', 'embed/', $request->input('video_url')) ?? "";
        $categories = $request->input('categories') ?? [];
        $tag_input = $request->input('tags') ?? [];

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
            $tags = PostHelper::check_tags_input($tag_input);

            $store = new Posts;
            $store->title = $request->input('title');
            $store->slug = $slug;
            $store->post_type = 'video';
            $store->content = $request->input('content');
            $store->featured_image = $request->input('featured_image');
            $store->author = app()->OAuth->Auth()->master_id;
            $store->status = $request->get('status');
            $store->published_date = $published_date;
            $store->save();

            $meta_contents = array();
            $metas[] = ['name' => 'meta_title', 'value' => $meta_title];
            $metas[] = ['name' => 'meta_desc', 'value' => $meta_desc];
            $metas[] = ['name' => 'meta_keyword', 'value' => $meta_keyword];
            $metas[] = ['name' => 'video_url', 'value' => $video_url];

            foreach ($categories as $cat) {
                $metas[] = ['name' => 'category', 'value' => $cat];
            }
            foreach ($tags as $tag) {
                $metas[] = ['name' => 'tag', 'value' => $tag];
            }

            foreach ($metas as $meta) {
                if ($meta['value'] != '') {
                    $meta_contents[] = [ 'post_id'=>$store->id, 'key'=> $meta['name'], 'value'=> $meta['value'] ];
                }
            }

            PostMeta::insert($meta_contents);

            DB::commit();
            return redirect(route('panel.video__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success'])->send();         
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.gallery__index'))->with(['msg' => 'Error saving', 'status' => 'warning'])->send();
        }
    }

    /**
     * Show the form for editing video.
     * @param $id
     * @return Response
     */
    public function viewVideo($id)
    {
        $page_meta_title = 'Gallery';
        $act = 'Edit';
        $action = $this->prefix.'update-video/'.$id;
        $video = Posts::where('id', $id)->first();
        if (isset($video)) {

            $post_metas = PostMeta::where('post_id',$video->id)->get();            
            $post_metas = $this->readMetas($post_metas);
            
            $meta_desc      = $post_metas->meta_desc ?? '';
            $meta_title     = $post_metas->meta_title ?? '';
            $meta_keyword   = $post_metas->meta_keyword ?? '';
            $tags = PostHelper::get_post_tag($video->id, 'id');  

            $alltag = Tags::orderBy('created_at','desc')->get();
            $title = $video->title;
            $content = $video->content;
            $video_url = $post_metas->video_url ?? '';

            $featured_img = $video->featured_image;
            $status = $video->status;
            $published_date = $video->published_date;
            $item_id = $video->id;
            return view('video::admin.video_edit')->with(['item_id' => $item_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'video' => $video , 'title' => $title, 'content' => $content,'alltag'=>$alltag, 'selected_tag' => $tags, 'featured_image' => $video->featured_image, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_date' => $published_date, 'video_url' => $video_url]);
        } else {
            return redirect(route('panel.gallery__index'))->with(['msg' => 'video Not Found', 'status' => 'danger']);
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
     * Update the specified video in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updateVideo(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ], PostHelper::validation_messages());

        $categories = $request->input('categories') ?? [] ;
        $published_date = $request->input('published_date');
        if ($published_date == 'immediately') {
            $published_date = Carbon::now()->toDateTimeString();
        }

        DB::beginTransaction();
        try {
            $tag_input = $request->input('tags') ?? [];
            $tags = PostHelper::check_tags_input($tag_input);

            $request->request->add(['tags'=>$tags]);

            $update = Posts::where('id', $id)->first();
            $update->title = $request->input('title');
            $update->content = $request->input('content');
            $update->featured_image = $request->input('featured_image');
            $update->status = $request->input('status');
            $update->published_date = $published_date;
            $update->update();
            
            $newMeta = false;
            $post_metas = PostMeta::where('post_id',$id)->get();
            $meta_fields = ['meta_title', 'meta_desc', 'meta_keyword', 'video_url' ];

            // save tags meta 
            PostHelper::save_post_meta_tag($update->id, $tags);
            // save categories meta 
            PostHelper::save_post_meta_category($update->id, $categories);

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
                        $field->value = $value ?? '';
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
            return redirect(route('panel.video__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.video__view', $id))->with(['msg' => 'Save error', 'status' => 'alert']);
        }

    }

    /**
     * Remove the specified video from storage.
     * @param $id
     * @return Response
     */
    public function removeVideo($id)
    {
        // $this->VideoHelper->delete_video($id);
        $video = Posts::where('id',$id)->first();
        $video->deleted = 1;
        if( $video->save() )
        {
            return redirect(route('panel.gallery__index'))->with(['msg' => 'Deleted', 'status' => 'success']);
        }
        return redirect(route('panel.gallery__index'))->with(['msg' => 'Delete error', 'status' => 'warning']);
    }

    /**
     * Remove multiple video from storage.
     * @param Request $request
     * @return Response
     */
    public function massDeleteVideo(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 1;
                if (!$delete->save()) {
                    return redirect(route('panel.gallery__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.gallery__index'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'videos']);
            }
        }
        return redirect(route('panel.gallery__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
        
        $query = Tags::orderBy($col,$direction);
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
        $tag = Tags::where('id', $id)->first();
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
        $update = Tags::where('id', $id)->first();
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
