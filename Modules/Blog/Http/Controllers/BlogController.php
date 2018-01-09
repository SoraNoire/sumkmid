<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Blog\Entities\Page;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Tags;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\PostTag;
use Modules\Blog\Entities\Media;
use Modules\Blog\Entities\Files;
use Modules\Blog\Entities\Option;
use Modules\Blog\Entities\Slider;
use Modules\Blog\Http\Helpers\PostHelper;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Image;
use View;
use Validator;
use Illuminate\Support\Facades\Input;


class BlogController extends Controller
{
    private $prefix;

    public function __construct(){
        $this->PostHelper = new PostHelper;
        $this->prefix = 'admin/blog/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'blog');
        View::share('tinymceApiKey', config('app.tinymce_api_key'));
    }
    
    /**
     * Display a listing of event (Dashboard).
     * @return Response
     */
    public function dashboard(){
        $page_meta_title = 'Events';
        return view('event::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Display a listing of post.
     * @return Response
     */
    public function index(){
        $page_meta_title = 'Posts';
        return view('blog::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single posts.
     * @param  $slug
     * @return Response
     */
    public function show_post($slug){
        $page_meta_title = 'Single Post';
        $post = Posts::where('slug', $slug)->first();
        if (isset($post)) {
            $tag = PostHelper::get_post_tag($post->id);
            $category = PostHelper::get_post_category($post->id);

            $option = json_decode($post->option);
            $files = $option->files;
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $post->status;
            $published_at = $post->published_at;

            return view('blog::admin.single')->with(['page_meta_title' => $page_meta_title, 'post' => $post, 'tag' => $tag, 'category' => $category, 'files' => $files, 'meta_keyword' => $meta_keyword, 'meta_title' => $meta_title, 'meta_desc' => $meta_desc, 'published_at' => $published_at]);
        } else {
            return redirect($this->prefix.'posts')->with('msg', 'Post Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get posts for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxposts(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('post_type','post')->where('deleted',0)->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();

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
     * Show the form for creating a new post.
     * @return Response
     */
    public function addPost()
    {
        $page_meta_title = 'Posts';
        $alltag = Tags::orderBy('created_at','desc')->get();

        return view('blog::admin.post_add')->with(['page_meta_title' => $page_meta_title, 'alltag' => $alltag]);
    }

    /**
     * Store a newly created post in storage.
     * @param  Request $request
     * @return Response
     */
    public function addPostPost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ], PostHelper::validation_messages());

        $categories = $request->input('categories') ?? [] ;
        $meta_title = $request->input('meta_title');
        $meta_desc = $request->input('meta_desc');
        $meta_keyword = $request->input('meta_keyword');
        $file_label = $request->get('file_label');
        $file_name = $request->get('file_name');

        $files = array();
        for ($i=0; $i < count($file_name); $i++) { 
            $files[] = [ 'file_name' => ($file_name[$i] ?? ''), 'file_label' => ($file_label[$i] ?? '') ];
        }
        $files = json_encode($files);

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
            $tag_input = $request->input('tags') ?? [];
            $tags = PostHelper::check_tags_input($tag_input);

            $store = new Posts;
            $store->title = $request->input('title');
            $store->slug = $slug;
            $store->post_type = 'post';
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
            $metas[] = ['name' => 'files', 'value' => $files];
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
            return redirect(route('panel.post__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.post__index'))->with(['msg' => 'Save Error'.$e, 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing post.
     * @param $id
     * @return Response
     */
    public function viewPost($id)
    {
        $page_meta_title = 'Posts';
        $act = 'Edit';
        $action = $this->prefix.'update-post/'.$id;
        $post = Posts::where('id', $id)->first();
        if (isset($post)) {

            $post_metas = PostMeta::where('post_id',$post->id)->get();            
            $post_metas = $this->readMetas($post_metas);
            
            $meta_desc      = $post_metas->meta_desc ?? '';
            $meta_title     = $post_metas->meta_title ?? '';
            $meta_keyword   = $post_metas->meta_keyword ?? '';
            $files = json_decode($post_metas->files);

            $tags = PostHelper::get_post_tag($post->id, 'id');            

            $title = $post->title;
            $content = $post->content;
            $alltag = Tags::get();

            $featured_image = $post->featured_image;
            $status = $post->status;
            $published_date = $post->published_date;
            $item_id = $post->id;

            return view('blog::admin.post_edit')->with(['isEdit'=>true,'item_id' => $item_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'post' => $post , 'title' => $title, 'content' => $content, 'alltag' => $alltag, 'selected_tag' => $tags, 'featured_image' => $featured_image, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_date' => $published_date, 'files' => $files]);
        } else {
            return redirect(route('panel.post__index'))->with(['msg' => 'Post Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified post in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updatePost(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ], PostHelper::validation_messages());

        $file_label = $request->get('file_label');
        $file_name = $request->get('file_name');
        $categories = $request->input('categories') ?? [] ;

        $files = array();
        for ($i=0; $i < count($file_name); $i++) { 
            $files[] = [ 'file_name' => ($file_name[$i] ?? ''), 'file_label' => ($file_label[$i] ?? '') ];
        }
        $files = json_encode($files);

        $post_metas = PostMeta::where('post_id',$id)->get();
        $post_metas = $this->readMetas($post_metas);

        $published_date = $request->input('published_date');
        if ($published_date == 'immediately') {
            $published_date = Carbon::now()->toDateTimeString();
        }

        DB::beginTransaction();
        try {
            $tag_input = $request->input('tags') ?? [];
            $tags = PostHelper::check_tags_input($tag_input);

            $request->request->add(['files'=>json_encode($files)]);
            $request->request->add(['tags'=>$tags]);

            $post_metas = PostMeta::where('post_id',$id)->get();
            $update = Posts::where('id', $id)->first();
            $update->title = $request->input('title');
            $update->content = $request->input('content');
            $update->featured_image = $request->input('featured_image');
            $update->status = $request->input('status');
            $update->published_date = $published_date;
            $update->update();
            
            $newMeta = false;
            $post_metas = PostMeta::where('post_id',$id)->get();
            $meta_fields = ['meta_title', 'meta_desc', 'meta_keyword', 'files' ];

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
            return redirect(route('panel.post__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.post__view', $update->id))->with(['msg' => 'Save error'.$e, 'status' => 'danger']);
        }
    }

    /**
     * Change post status delete to 1.
     * @param $id
     * @return Response
     */
    public function removePost($id)
    {
        $delete = Posts::find($id);
        if ($delete){
            $delete->deleted = 1;
            $delete->save();
            return redirect(route('panel.post__index'))->with(['msg' => 'Deleted', 'status' => 'success']);
        }
        return redirect(route('panel.post__index'))->with(['msg' => 'Delete error', 'status' => 'danger']);
    }

    /**
     * Change multiple post status delete to 1.
     * @param  Request $request
     * @return Response
     */
    public function massdeletePost(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 1;
                if (!$delete->save()) {
                    return redirect(route('panel.post__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.post__index'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.post__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Remove the specified post from storage.
     * @param $id
     * @return Response
     */
    public function destroy_post($id)
    {
        $this->PostHelper->delete_post($id);
    }

    /**
     * Remove multiple post from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_post(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->PostHelper->delete_post($id, 'bulk');
        }
        return redirect($this->prefix.'posts')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display list of files
     **/
    public function files(){
        $page_meta_title = 'Files';
        return view('blog::admin.file')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Get files for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_file(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';

        $medias = Files::orderBy($col,$direction);
        $output['recordsTotal'] = $medias->count();
        $search = $request->search['value'];
        if (isset($search)) {
            $medias = $medias->where('label', 'like', '%'.$search.'%');   
        }
        $output['data'] = $medias->offset($request['start'])->limit($request['length'])->get();

        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = $request['length'];
        $output['start']=$request['start'];

        return $output;
    }

    /**
     * Store a file in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_file(Request $req){
        $this->validate($req, [
            'fileUpload.*' => 'mimes:pdf,doc,dot,docx,xlsx,xml,ppt,ppa,pptx,ppsx,mdb,txt,zip,rar',
        ], PostHelper::validation_messages());
        
        if ($req->hasFile('fileUpload')) {
            try {
                $file = $req->file('fileUpload');
                $fileNames = [];
                foreach ($file as $file) {
                    $oriName = $file->getClientOriginalName();
                    $oriName = strtolower($oriName);
                    $name = time().'.'.$file->getClientOriginalExtension();
                    $name = strtolower($name);
                    PostHelper::putFile($file, 'files', $name);
                    $fileNames[] = [ 'name' => $name , 'oriName' => $oriName ];

                    $store = new Files();
                    $store->name = $name;
                    $store->label = $oriName;
                    $store -> save();
                    echo "Success adding file ".$name;
                }
            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                
            }
        }else{
            return  "Error adding file";
        }
    }

    /**
     * Show form edit file
     * @param $id
     * @return redirect
     **/
    public function edit_file($id){
        $page_meta_title = 'Files';
        $file = Files::where('id', $id)->first();
        if (isset($file)) {
            $label = $file->label;

            return view('blog::admin.file_edit')->with(['label' => $label, 'id' => $id, 'page_meta_title' => $page_meta_title]);
        }
        return redirect(route('panel.file__index'))->with(['msg' => 'File Not Found', 'status' => 'danger']);
    }

    /**
     * Update file on storage
     * @param Request $request, $id
     * @return redirect
     **/
    public function update_file(Request $request, $id){
        $file = Files::where('id', $id)->first();
        if (isset($file)) {
            $file->label = $request->input('label');
            if ($file->save()) {
                return redirect(route('panel.file__view', $id))->with(['msg' => 'Saved', 'status' => 'success']);
            }
            return redirect(route('panel.file__view', $id))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect(route('panel.file__index'))->with(['msg' => 'Save Error. File Not Found', 'status' => 'danger']);
    }

    /**
     * Remove the specified file from storage.
     * @param $id
     * @return Response
     */
    public function destroy_file($id){
        $file = Files::where('id',$id)->first();
        if($file->name != ""){
            if(Storage::disk('s3')->exists('shbtmdev/files/'.$file->name)){
                $this->PostHelper->deleteFile($file->name, 'files');
            }
        }
        if ($file -> delete()) {
            return "File deleted";
        }else{
            return "failed deleting file";
        }
    }

    /**
     * Remove multiple file from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_file(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $file = Files::where('id', $id)->first();
            if (isset($file)) {
                if($file->name != ""){
                    if(Storage::disk('s3')->exists('shbtmdev/files/'.$file->name)){
                        $this->PostHelper->deleteFile($file->name, 'files');
                    }
                }
                if ($file->delete()) {
                    // do nothing
                } else {
                    return redirect( route('panel.file__index') )->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect( route('panel.file__index') )->with(['msg' => 'Delete Error.Some File Not Found', 'status' => 'danger']);
            }
        }
        return redirect( route('panel.file__index') )->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of category.
     * @return Response
     */
    public function category(){
        $page_meta_title = 'Category';
        $category = Category::get();
        return view('blog::admin.category')->with(['page_meta_title' => $page_meta_title, 'category' => $category]);
    }



    // tags

    public function tags(){
        $page_meta_title = 'Tags';
        $data = Tags::get();
        return view('blog::admin.tags')->with(['page_meta_title' => $page_meta_title, 'tags' => $data]);
    }

    public function ajaxTags(Request $request){
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

    public function addTag()
    {
        $page_meta_title = 'Tag';
        $act = 'New';
        $name = '';
        return view('blog::admin.tagform')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'name' => $name, 'isEdit'=>false]);
    }

    public function addTagPost(Request $request)
    {
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new Tags;
        $store->name = $request->input('name');
        $store->slug = $slug;
        if ($store->save()){
            return redirect(route('panel.tag__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.tag__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * edit category
     * @param $id
     * @return Response
     */
    public function viewTag($id){
        $page_meta_title = 'Tag';
        $act = 'Edit';
        $tag = Tags::where('id', $id)->first();
        if (isset($tag)) {
            $name = $tag->name;
            return view('blog::admin.tagform')->with(['id'=>$id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'tag' => $tag, 'name' => $name, 'isEdit'=>true]);
        } else {
            return redirect(route('panel.tag__index'))->with('msg', 'Tag Not Found')->with('status', 'danger');
        }
    }

    /**
     * Update category
     * @param  Request $request, $id
     * @return Response
     */
    public function updateTag(Request $request, $id){
        $update = Tags::where('id', $id)->first();
        $update->name = $request->input('name');
        if ($update->save()){
            return redirect(route('panel.tag__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.tag__view', $id))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * remove Category
     *
     * @return void
     * @author 
     **/
    public function removeTag($id){
        $this->PostHelper->delete_tag($id);
    }

    /**
     * mass delete cat
     *
     * @return void
     * @author 
     **/
    public function massdeleteTag(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->PostHelper->delete_tag($id, 'bulk');
        }
        return redirect(route('panel.tag__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }


    // categories

    public function categories(){
        $page_meta_title = 'Categories';
        $categories = Categories::get();
        return view('blog::admin.categories')->with(['page_meta_title' => $page_meta_title, 'categories' => $categories]);
    }

    public function ajaxCategories(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Categories::orderBy($col,$direction);
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

    public function addCategory()
    {
        $page_meta_title = 'Category';
        $act = 'New';
        $name = '';
        $desc = '';
        $allparent = PostHelper::get_category_parent();
        return view('blog::admin.catform')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'name' => $name,'desc'=>$desc, 'allparent' => $allparent , 'isEdit'=>false]);
    }

    public function addCategoryPost(Request $request)
    {
        $parent = $request->get('parent');
        if ($parent == 'none') {
            $parent = null;
        }
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new Categories;
        $store->name = $request->input('name');
        $store->description = $request->input('description') ?? '';
        $categoryajax = $request->input('catjax') ?? false;
        $store->slug = $slug;
        $store->parent = $parent;
        if ($store->save()){

            if ( $categoryajax ){
                return response("<li><label><input checked selected name='categories[]' type='checkbox' value='$store->id'>$store->name</label></li>");
            }

            return redirect(route('panel.category__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);

        } else {
            return redirect(route('panel.category__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * edit category
     * @param $id
     * @return Response
     */
    public function viewCategory($id){
        $page_meta_title = 'Category';
        $act = 'Edit';
        $action = $this->prefix.'update-category/'.$id;
        $category = Categories::where('id', $id)->first();
        // dd($category->id);
        if (isset($category)) {
            $maincategory = Categories::where('parent', null)->get(); 
            $allparent = PostHelper::get_category_parent($category->id);
            $name = $category->name;
            $desc = $category->description;
            $category_id = $category->id;
            return view('blog::admin.catform')->with(['category_id' => $category_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action,'desc'=>$desc, 'category' => $category, 'name' => $name, 'allparent' => $allparent,'isEdit'=>true]);
        }else {
            return redirect(route('panel.category__index'))->with(['msg' => 'Category Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update category
     * @param  Request $request, $id
     * @return Response
     */
    public function updateCategory(Request $request, $id){
        $parent = $request->get('parent');
        if ($parent == 'none') {
            $parent = null;
        }
        $update = Categories::where('id', $id)->first();
        $update->name = $request->input('name');
        $update->description = $request->input('description') ?? '';
        $update->parent = $parent;
        if ($update->save()){
            return redirect(route('panel.category__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.category__view', $update->id))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect(route('panel.category__index'));
    }

    /**
     * remove Category
     *
     * @return void
     * @author 
     **/
    public function removeCategory($id){
        $this->PostHelper->delete_category($id);
    }

    /**
     * mass delete cat
     *
     * @return void
     * @author 
     **/
    public function massdeleteCategory(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->PostHelper->delete_category($id, 'bulk');
        }
        return redirect(route('panel.category__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
        
        $query = Category::orderBy($col,$direction);
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
        $page_meta_title = 'Category';
        $act = 'New';
        $action = $this->prefix.'store-category';
        $name = ''; 
        $allparent = PostHelper::get_category_parent();
        return view('blog::admin.category_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name, 'allparent' => $allparent]);
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
        $store = new Category;
        $store->name = $request->input('name');
        $store->slug = $slug;
        $store->parent = $parent;
        if ($store->save()){
            return redirect(route('panel.category__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.category__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
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
        $store = new Categories;
        $store->name = $name;
        $store->slug = $slug;
        $store->description = '';
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
        $page_meta_title = 'Category';
        $act = 'Edit';
        $action = $this->prefix.'update-category/'.$id;
        $category = Category::where('id', $id)->first();

        if (isset($category)) {
            $maincategory = Category::where('parent', null)->get(); 
            $allparent = PostHelper::get_category_parent($category->id);
            $name = $category->name;
            $category_id = $category->id;
            return view('blog::admin.category_form')->with(['category_id' => $category_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'category' => $category, 'name' => $name, 'allparent' => $allparent]);
        }else {
            return redirect(route('panel.category__index'))->with(['msg' => 'Category Not Found', 'status' => 'danger']);
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
        $update = Category::where('id', $id)->first();
        $update->name = $request->input('name');
        $update->parent = $parent;
        if ($update->save()){
            return redirect(route('panel.category__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.category__view', $update->id))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect(route('panel.category__index'));
    }

    /**
     * Remove the specified category from storage.
     * @param $id
     * @return Response
     */
    public function destroy_category($id){
        $this->PostHelper->delete_category($id);
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
            $this->PostHelper->delete_category($id, 'bulk');
        }
        return redirect(route('panel.category__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of tag.
     * @return Response
     */
    public function tag(){
        $page_meta_title = 'Tag';
        return view('blog::admin.tag')->with(['page_meta_title' => $page_meta_title]);
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
        
        $query = Tag::orderBy($col,$direction);
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
        $page_meta_title = 'Tag';
        $act = 'New';
        $action = $this->prefix.'store-tag';
        $name = '';
        return view('blog::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name]);
    }

    /**
     * Store a newly created tag in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_tag(Request $request){
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new Tag;
        $store->name = $request->input('name');
        $store->slug = $slug;
        if ($store->save()){
            return redirect(route('panel.tag__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.tag__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing the specified tag.
     * @param $id
     * @return Response
     */
    public function edit_tag($id){
        $page_meta_title = 'Tag';
        $act = 'Edit';
        $action = $this->prefix.'update-tag/'.$id;
        $tag = Tag::where('id', $id)->first();
        if (isset($tag)) {
            $name = $tag->name;
            return view('blog::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'tag' => $tag, 'name' => $name]);
        } else {
            return redirect(route('panel.tag__index'))->with('msg', 'Tag Not Found')->with('status', 'danger');
        }
    }

    /**
     * Update the specified tag in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_tag(Request $request, $id){
        $update = Tag::where('id', $id)->first();
        $update->name = $request->input('name');
        if ($update->save()){
            return redirect(route('panel.tag__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.tag__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Remove the specified tag from storage.
     * @param $id
     * @return Response
     */
    public function destroy_tag($id){
        $this->PostHelper->delete_tag($id);
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
            $this->PostHelper->delete_tag($id, 'bulk');
        }
        return redirect(route('panel.tag__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of media.
     * @return Response
     */
    public function media(){
        $page_meta_title = 'Media';
        return view('blog::admin.media')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Get medias for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_media(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';

        $medias = Media::orderBy($col,$direction);
        $output['recordsTotal'] = $medias->count();
        $search = $request->search['value'];
        if (isset($search)) {
            $medias = $medias->where('name', 'like', '%'.$search.'%');   
        }
        $output['data'] = $medias->offset($request['start'])->limit($request['length'])->get();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = $request['length'];
        $output['start']=$request['start'];

        return $output;
    }

    /**
     * Store a newly created media in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_media(Request $req){
        $this->validate($req, [
            'media.*' => 'image|max:3000|mimes:jpg,jpeg,png,gif',
        ], PostHelper::validation_messages());
        if ($req->hasFile('media')) {
            try {
                $file = $req->file('media');
                foreach ($file as $file) {
                    $fileName = time();
                    $name = $fileName.'.'.$file->getClientOriginalExtension();
                    $name = strtolower($name);

                    PostHelper::putImage($file, 'media', $fileName);

                    $media = new Media();
                    $media -> name = $name;
                    $media -> save();
                    echo "Success adding media ".$name;
                }
            } catch (Illuminate\Filesystem\FileNotFoundException $e) {

            }
        }else{
            return  "Failed adding media";
        }
    }

    /**
     * Remove the specified media from storage.
     * @param $id
     * @return Response
     */
    public function destroy_media($id){
        $media = Media::where('id',$id)->first();
        if($media->name != ""){
            if(Storage::disk('s3')->exists('shbtm/media/'.$media->name)){
                $this->PostHelper->deleteImage($media->name, 'media');
            }
        }
        if ($media -> delete()) {
            return "Media deleted";
        }else{
            return "failed deleting media";
        }
    }

    /**
     * Remove multiple media from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_media(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $media = Media::where('id', $id)->first();
            if (isset($media)) {
                if($media->name != ""){
                    if(Storage::disk('s3')->exists('shbtm/media/'.$media->name)){
                        $this->PostHelper->deleteImage($media->name, 'media');
                    }
                }
                if ($media->delete()) {
                    // do nothing
                } else {
                    return redirect($this->prefix.'media')->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect($this->prefix.'media')->with(['msg' => 'Delete Error.Some Media Not Found', 'status' => 'danger']);
            }
        }
        return redirect($this->prefix.'media')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of pages.
     * @return Response
     */
    public function pages()
    {
        $page_meta_title = 'Page';
        return view('blog::admin.pages')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single page.
     * @param $slug
     * @return Response
     */
    public function show_page($slug){
        $page_meta_title = 'Page';
        $page = Posts::where('slug', $slug)->first();
        if (isset($page)) {
            return view('blog::admin.single_page')->with(['page_meta_title' => $page_meta_title, 'page' => $page]);
        } else {
            return redirect(route('panel.page__index'))->with('msg', 'Page Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get pages for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxPages(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('post_type','page')->where('deleted',0)->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();

        $newdata = array();
        foreach ($output['data'] as $data) {
            $u= app()->OAuth->user($data->author);
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
     * Show the form for creating a new page.
     * @return Response
     */
    public function addPage()
    {
        $page_meta_title = 'Page';
        $media = Media::orderBy('created_at','desc')->get();
        $templates = PostHelper::get_page_templates_list();

        return view('blog::admin.page_add')->with(['page_meta_title' => $page_meta_title, 'media' => $media, 'templates' => $templates]);
    }

    /**
     * Store a newly created page in storage.
     * @param  Request $request
     * @return Response
     */
    public function addPagePost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ], PostHelper::validation_messages());

        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $body = $request->input('content') ?? '';
        $featured_img = $request->input('featured_image');
        $status = $request->get('status');
        $published_at = $request->input('published_date');
        $meta_title = $request->input('meta_title');
        $meta_desc = $request->input('meta_desc');
        $meta_keyword = $request->input('meta_keyword');
        $page_template = $request->get('page_template');

        if ($published_at == 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Posts::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::beginTransaction();
        try {
            $store = new Posts;
            $store->title = $title;
            $store->slug = $slug;
            $store->post_type = 'page';
            $store->content = $body;
            $store->featured_image = $featured_img;
            $store->author = app()->OAuth->Auth()->master_id;
            $store->status = $status;
            $store->published_date = $published_at;
            $store->save();

            $meta_contents = array();
            $metas[0] = ['name' => 'meta_title', 'value' => $meta_title];
            $metas[1] = ['name' => 'meta_desc', 'value' => $meta_desc];
            $metas[2] = ['name' => 'meta_keyword', 'value' => $meta_keyword];
            $metas[3] = ['name' => 'page_template', 'value' => $page_template];

            foreach ($metas as $meta) {
                if ($meta['value'] != '') {
                    $meta_contents[] = [ 'post_id'=>$store->id, 'key'=> $meta['name'], 'value'=> $meta['value'] ];
                }
            }

            PostMeta::insert($meta_contents);

            DB::commit();
            return redirect(route('panel.page__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.page__index'))->with(['msg' => 'Save Error ', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing the specified page.
     * @param $id
     * @return Response
     */
    public function viewPage($id)
    {
        $page = Posts::where('id', $id)->first();
        if (isset($page)) {

            $post_metas = PostMeta::where('post_id',$page->id)->get();
            $post_metas = $this->readMetas($post_metas);
            $media = Media::orderBy('created_at','desc')->get();
            $templates = PostHelper::get_page_templates_list();

            return view('blog::admin.page_edit')->with([
                        'page_meta_title' => 'Page',
                        'act' => 'Edit',
                        'action' => $this->prefix.'update-page/'.$id ?? '',
                        'page' => $page,
                        'title' => $page->title,
                        'content' => $page->content,
                        'media' => $media,
                        'page_template' => $post_metas->page_template ?? '',
                        'featured_image' => $page->featured_image ?? '',
                        'meta_desc' => $post_metas->meta_desc ?? '',
                        'meta_title' => $post_metas->meta_title ?? '',
                        'meta_keyword' => $post_metas->meta_keyword ?? '',
                        'status' => $page->status ?? 0,
                        'published_date' => $page->published_date ?? '',
                        'isEdit'=> true,
                        'templates' => $templates
                    ]);
        } else {
            return redirect(route('panel.page__index'))->with(['msg' => 'Page Not Found', 'status' => 'danger']);
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
     * Update the specified page in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updatePage(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ], PostHelper::validation_messages());

        $update = Posts::where('id', $id)->first();
        $update->title = $request->input('title');
        $update->content = $request->input('content') ?? '';
        $update->featured_image = $request->input('featured_image');
        $update->status = $request->input('status');
        $update->published_date = Carbon::parse($request->input('published_date'))->toDateTimeString();

        if ( $update->update()) {
            $newMeta = false;
            $post_metas = PostMeta::where('post_id',$id)->get();
            $meta_fields = ['meta_title', 'meta_desc', 'meta_keyword', 'page_template' ];

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

            return redirect(route('panel.page__view', $update->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('panel.page__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    public function removePage($id)
    {
        $delete = Posts::find($id);
        if ($delete){
            $delete->deleted = 1;
            $delete->save();
            return redirect(route('panel.page__index'))->with(['msg' => 'Deleted', 'status' => 'success']);
        }
        return redirect(route('panel.page__index'))->with(['msg' => 'Delete error', 'status' => 'danger']);
    }

    /**
     * Remove the specified page from storage.
     * @param $id
     * @return Response
     */
    public function destroy_page($id)
    {
        $page = Posts::where('id', $id)->first();
        if (isset($page)) {
            if ($page->delete()) {
                return redirect(route('panel.page__index'))->with(['msg' => 'Deleted', 'status' => 'success']);
            } else {
                return redirect(route('panel.page__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        } else {
            return redirect(route('panel.page__index'))->with(['msg' => 'Page Not Found', 'status' => 'danger']);
        }

    }

    /**
     * Remove multiple page from storage.
     * @param  Request $request
     * @return Response
     */
    public function massdeletePage(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 1;
                if (!$delete->save()) {
                    return redirect(route('panel.page__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.page__index'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.page__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }
    // end page controller

    // trash controller
    /**
     * Display list of deleted post.
     * @param  Request $request
     * @return Response
     */
    public function trash()
    {
        $page_meta_title = 'Trash';
        return view('blog::admin.trash')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Get posts for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxtrashPosts(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('deleted',1)->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();

        $newdata = array();
        foreach ($output['data'] as $data) {
            $u= app()->OAuth->user($data->author);
            $name = $u->username??'admin';
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
     * Remove permanent multiple page from storage.
     * @param  Request $request
     * @return Response
     */
    public function deleteTrash($id)
    {
        $delete = Posts::find($id);
        if ($delete){  
            $delete->delete();  
            return redirect(route('panel.post.trash__index'))->with(['msg' => 'Deleted', 'status' => 'success']);
        }
        return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete error', 'status' => 'danger']);
    }

     /**
     * Restore multiple page from storage.
     * @param  Request $request
     * @return Response
     */
    public function restoreTrash($id)
    {
        $post = Posts::find($id);
        if ($post){  
            $post->deleted = 0;
            $post->save();  
            return redirect(route('panel.post.trash__index'))->with(['msg' => 'Restored', 'status' => 'success']);
        }
        return redirect(route('panel.post.trash__index'))->with(['msg' => 'Restore Error', 'status' => 'danger']);
    }

     /**
     * Remove permanent multiple post from storage.
     * @param  Request $request
     * @return Response
     */
    public function massdeleteTrash(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                if (!$delete->delete()) {
                    return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

     /**
     * Restore multiple post from storage.
     * @param  Request $request
     * @return Response
     */
    public function massrestoreTrash(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 0;
                if (!$delete->save()) {
                    return redirect(route('panel.post.trash__index'))->with(['msg' => 'Restore Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.post.trash__index'))->with(['msg' => 'Restore Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.post.trash__index'))->with(['msg' => 'Restore Success', 'status' => 'success']);
    }

    /**
     * Empty trash.
     * @return Response
     */
    public function emptyTrash()
    {
        $posts = Posts::where('deleted', 1)->get();
        foreach ($posts as $post) {
            if ($post) {
                if (!$post->delete()) {
                    return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.post.trash__index'))->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.post.trash__index'))->with(['msg' => 'ResDeletetore Success', 'status' => 'success']);
    }
    // end trash controller

    /**
     * Get all category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
         return PostHelper::get_category_parent($category_id);
    }

    /**
     * Get all category for list on post form.
     * @param  $post_id
     * @return Response
     */
    public static function get_all_category($post_id = ''){
        return PostHelper::get_all_category($post_id);
    }
    
    /**
     * Show site setting form.
     * @return Response
     */
    public function site_setting_view(){
        $page_meta_title = 'Site Setting';

        $gtag_manager = Option::where('key', 'gtag_manager')->first()->value ?? '';
        $fb_pixel = Option::where('key', 'fb_pixel')->first()->value ?? '';
        $link_fb = Option::where('key', 'link_fb')->first()->value ?? '';
        $link_tw = Option::where('key', 'link_tw')->first()->value ?? '';
        $link_in = Option::where('key', 'link_in')->first()->value ?? '';
        $link_gplus = Option::where('key', 'link_gplus')->first()->value ?? '';
        $link_ig = Option::where('key', 'link_ig')->first()->value ?? '';
        $link_yt = Option::where('key', 'link_yt')->first()->value ?? '';
        $program = Option::where('key', 'list_program')->first()->value ?? '';
        $footer_desc = Option::where('key', 'footer_desc')->first()->value ?? '';
        $instagram_token = Option::where('key', 'instagram_token')->first()->value ?? '';
        $email = Option::where('key', 'email')->first()->value ?? '';
        $post = Option::where('key', 'post_section')->first()->value ?? '';
        if ($post != '') {
            $post = json_decode($post);
        } else {
            $post['use_gallery'] = 1;
            $post['category'] = 0;
            $post = json_encode($post);
            $post = json_decode($post);
        }
        $post->check = '';
        if (isset($post->use_gallery) && $post->use_gallery == 1) {
            $post->check = 'checked';
        } 

        $all_cat = PostHelper::get_all_categories(['video', 'gallery']);
        
        $about_us = Option::where('key', 'about_us')->first()->value ?? '';
        if ($about_us != '') {
            $about_us = json_decode($about_us);
        }

        $social_feed = Option::where('key', 'socfeed_section')->first()->value ?? '';
        if ($social_feed != '') {
            $social_feed = json_decode($social_feed);
        }

        $mentor = Option::where('key', 'mentor_section')->first()->value ?? '';
        if ($mentor != '') {
            $mentor = json_decode($mentor);
        }

        $program_section = Option::where('key', 'program_section')->first()->value ?? '';
        if ($program_section != '') {
            $program_section = json_decode($program_section);
        }

        $program_structure = '';
        if ($program != '') {
            $program = json_decode($program);
            foreach ($program as $key) {
                $program_structure .= '<li class="dd-item" data-id="';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '" data-title="';

                if (isset($key->title)) {
                    $program_structure .= $key->title;
                }
                
                $program_structure .= '" data-description="';

                if (isset($key->description)) {
                    $program_structure .= $key->description;
                }
                
                $program_structure .= '" data-logo="';

                if (isset($key->logo)) {
                    $program_structure .= $key->logo;
                }
                
                $program_structure .= '" data-background="';

                if (isset($key->background)) {
                    $program_structure .= $key->background;
                }
                
                $program_structure .= '" data-url="';

                if (isset($key->url)) {
                    $program_structure .= $key->url;
                }

                $program_structure .= '"><div class="dd-handle dd3-handle">Drag</div><div class="program-item dd3-content panel panel-default" id="program';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '"><div class="program-title"><span>';

                if (isset($key->title)) {
                    $program_structure .= $key->title;
                }
                
                $program_structure .= '</span><a data-toggle="collapse" data-parent="#program-structure" href="#program-collapse-';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="program-collapse-';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '" class="collapse program-collapse panel panel-default"><div class="form-group"><label>Title</label><input class="form-control" type="text" name="title" value="';

                if (isset($key->title)) {
                    $program_structure .= $key->title;
                }
                
                $program_structure .= '"><label>URL</label><input class="form-control" type="text" name="url" value="';
                
                if (isset($key->url)) {
                    $program_structure .= $key->url;
                }

                $program_structure .= '"><label>Logo</label><div class="input-group"><input class="form-control" type="text" name="logo" value="';

                if (isset($key->logo)) {
                    $program_structure .= $key->logo;
                }
                
                $program_structure .= '" readonly="readonly" id="program-logo';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '"><span class="input-group-btn"><button class="btn btn-default program-media" type="button" data-tujuan="program-logo';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '">Browse media</button></span></div><label>Background</label><div class="input-group"><input class="form-control" type="text" name="background" value="';

                if (isset($key->background)) {
                    $program_structure .= $key->background;
                }
                
                $program_structure .= '" readonly="readonly" id="program-bg';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '"><span class="input-group-btn"><button class="btn btn-default program-media" type="button" data-tujuan="program-bg';

                if (isset($key->id)) {
                    $program_structure .= $key->id;
                }
                
                $program_structure .= '">Browse media</button></span></div><label>Description</label><textarea name="description" class="form-control">';

                if (isset($key->description)) {
                    $program_structure .= $key->description;
                }
                
                $program_structure .= '</textarea></div><a href="#" class="remove_item">Remove</a></div></div></li>';
            }
        }

        return view('blog::admin.setting')->with(['page_meta_title' => $page_meta_title, 'gtag_manager' => $gtag_manager, 'fb_pixel' => $fb_pixel, 'link_fb' => $link_fb, 'link_in' => $link_in, 'link_tw' => $link_tw, 'link_yt' => $link_yt, 'link_ig' => $link_ig, 'link_gplus' => $link_gplus, 'list_program' => $program_structure, 'program' => $program_section, 'footer_desc' => $footer_desc, 'post' => $post, 'all_cat' => $all_cat, 'email' => $email, 'about_us' => $about_us, 'instagram_token' => $instagram_token, 'social_feed' => $social_feed, 'mentor' => $mentor]);
    }

    /**
     * Save site setting.
     * @param  $post_id
     * @return Response
     */
    public function site_setting_save(Request $request){

        $about_us['title'] = $request->input('about_title');
        $about_us['text'] = $request->input('about_us');

        $post['title'] = $request->input('post_title');
        $post['use_gallery'] = $request->get('post_check');
        $post['category'] = $request->get('post_category');

        $program['title'] = $request->input('program_title');
        $program['desc'] = $request->input('program_desc');
        $program['button'] = $request->input('program_button');
        $program['url'] = $request->input('program_url');

        $socfeed['title'] = $request->input('socfeed_title');

        $mentor['title'] = $request->input('mentor_title');

        $settings[] = ['name' => 'link_fb', 'value' => $request->input('link_fb')];
        $settings[] = ['name' => 'link_tw', 'value' => $request->input('link_tw')];
        $settings[] = ['name' => 'link_gplus', 'value' => $request->input('link_gplus')];
        $settings[] = ['name' => 'link_ig', 'value' => $request->input('link_ig')];
        $settings[] = ['name' => 'link_in', 'value' => $request->input('link_in')];
        $settings[] = ['name' => 'link_yt', 'value' => $request->input('link_yt')];
        $settings[] = ['name' => 'gtag_manager', 'value' => $request->input('gtag_manager')];
        $settings[] = ['name' => 'fb_pixel', 'value' => $request->input('fb_pixel')];
        $settings[] = ['name' => 'post_section', 'value' => json_encode($post)];
        $settings[] = ['name' => 'program_section', 'value' => json_encode($program)];
        $settings[] = ['name' => 'about_us', 'value' => json_encode($about_us)];
        $settings[] = ['name' => 'socfeed_section', 'value' => json_encode($socfeed)];
        $settings[] = ['name' => 'mentor_section', 'value' => json_encode($mentor)];
        $settings[] = ['name' => 'instagram_token', 'value' => $request->input('instagram_token')];
        $settings[] = ['name' => 'footer_desc', 'value' => $request->input('footer_desc')];
        $settings[] = ['name' => 'email', 'value' => $request->input('email')];

        try {
            for ($i=0; $i < count($settings) ; $i++) { 
                $save = Option::where('key', $settings[$i]['name'])->first();
                if (isset($save)) {
                    $save -> value = $settings[$i]['value'];
                } else {
                    $save = new Option;
                    $save -> key = $settings[$i]['name'];
                    $save -> value = $settings[$i]['value'];
                }
                $save->save();
            }
            // SSOHelper::newsLog("Updating setting");
            return redirect(route('panel.setting.site__index'))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            // SSOHelper::newsLog("Someting when wrong when saving setting: ".$e);
            return redirect(route('panel.setting.site__index'))->with(['msg' => 'Save Error', 'status' => 'danger']);    
        }
    }

    /**
     * Store a program setting.
     * @param  Request $request
     * @return Response
     */
    public function save_program(Request $request)
    {
        $option = Option::where('key', 'list_program')->first();
        $program = $request->program;
        if (isset($option)) {
            $option -> value = $program;
        } else {
            $option = new Option;
            $option -> key = 'program';
            $option -> value = $program;
        }
        
        if ($option->save()) {
            return 'berhasil simpan';
        } else {
            return 'gagal menyimpan';
        }
    }

    // slider controller
    /**
     * Display a listing of slider.
     * @return Response
     */
    public function head_slider(){
        $page_meta_title = 'Slider';
        $sliders = Slider::orderBy('created_at','desc')->get();
        return view('blog::admin.slider') -> with(['page_meta_title' => $page_meta_title,'sliders' => $sliders]);
    }

    /**
     * Show the form for creating slider.
     * @return Response
     */
    public function new_slider_view(){
        $page_meta_title = 'Slider';
        
        return view('blog::admin.slider-add') -> with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Store newly created slider in storage.
     * @param  Request $request
     * @return Response
     */
    public function new_slider_act(Request $req){
        $title = $req->input('title');
        $description = $req->input('description');
        $slider_img = $req->input('slider_img');
        $btn_text = $req->input('btn_text');
        $btn_link = $req->input('btn_link');

        $slider = new Slider();
        $slider->title = $title;
        $slider->description = $description;
        $slider->image = $slider_img;
        $slider->btn_text = $btn_text;
        $slider->link = $btn_link;
        if ($slider -> save()) {
            return redirect(route('panel.slider__view', $slider->id)) -> with("msg","Berhasil Ditambahkan")-> with("status","success");
        }else{
            return redirect(route('panel.slider__index')) -> with("msg","Gagal Ditambahkan")-> with("status","success");    
        }
    }

    /**
     * Show the form for editing the specified slider.
     * @param  $id
     * @return Response
     */
    public function edit_slider_view($id){
        $page_meta_title = 'Slider';
        $slider = Slider::where('id',$id)->first();

        if (isset($slider)) {

            return view('blog::admin.slider-edit') -> with(['page_meta_title' => $page_meta_title, 'slider' => $slider]);
        }else {
            return redirect(route('panel.slider__index'))->with(['msg' => 'Slider tidak ditemukan', 'status' => '4']);
        }
    }

    /**
     * Update specified slider in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function edit_slider_act(Request $req, $id){
        $title = $req->input('title');
        $description = $req->input('description');
        $slider_img = $req->input('slider_img');
        $btn_text = $req->input('btn_text');
        $btn_link = $req->input('btn_link');

        $slider = Slider::where('id',$id)->first();
        $slider->title = $title;
        $slider->description = $description;
        $slider->image = $slider_img;
        $slider->btn_text = $btn_text;
        $slider->link = $btn_link;
        if ($slider -> save()) {
            return redirect(route('panel.slider__view', $slider->id)) -> with("msg","Berhasil Menyimpan")-> with("status","success");
        }else{
            return redirect(route('panel.slider__view', $slider->id)) -> with("msg","Gagal mengedit data photo")-> with("status","danger");
        }
    }

    /**
     * Remove specified slider in storage.
     * @param  $id
     * @return Response
     */
    public function hapus_slider($id){
        $slider = Slider::where('id',$id)->first();
        if ($slider -> delete()) {
            return redirect(route('panel.slider__index')) -> with("msg","Slider berhasil dihapus")-> with("status","success");
        }else{
            return redirect(route('panel.slider__index')) -> with("msg","Gagal menghapus Foto")-> with("status","danger");
        }
    }
    // end slider controller
}
