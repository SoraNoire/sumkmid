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
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Tags;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\PostTag;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Image;
use View;

class BlogController extends Controller
{
    private $prefix;

    public function __construct(){
        $this->PostHelper = new PostHelper;
        $this->prefix = 'admin/blog/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'blog');
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
    public function get_posts(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::orderBy($col,$direction);
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
     * Show the form for creating a new post.
     * @return Response
     */
    public function create_post()
    {
        $page_meta_title = 'Posts';
        $act = 'New';
        $action = $this->prefix.'store-post';

        $title = '';
        $body = '';
        $selected_tag = '';
        $featured_img = '';
        $media = Media::orderBy('created_at','desc')->get();
        $alltag = Tag::orderBy('created_at','desc')->get();
        $allcategory = PostHelper::get_all_category();
        $allparent = PostHelper::get_category_parent();
        $files = '';
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';

        return view('blog::admin.post_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'body' => $body, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'allcategory' => $allcategory, 'media' => $media, 'featured_img' => $featured_img, 'allparent' => $allparent, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'files' => $files]);
    }

    /**
     * Store a newly created post in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_post(Request $request)
    {
        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $body = $request->input('body');
        $category = $request->get('category');
        $tag = $request->input('tag');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $file_doc = $request->input('file_doc');
        $file_label = $request->input('file_label');
        $option['files'] = '';
        if (isset($file_doc)) {
            for ($i=0; $i < count($file_doc); $i++) {
                $option['files'][$i]['file_label'] = $file_label[$i];
                $option['files'][$i]['file_doc'] = $file_doc[$i];
            }
        }
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        if ($published_at = 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Posts::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::transaction(function() use ($title, $slug, $category, $tag, $body, $featured_img, $option, $status, $published_at) {
            if (isset($tag)) {
                // save tag to table tag
                $tag_id = array();
                foreach ($tag as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = Tag::where('slug', $tag_slug)->first();
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

            $store = new Posts;
            $store->title = $title;
            $store->slug = $slug;
            $store->body = $body;
            $store->featured_img = $featured_img;
            $store->author = 1;
            $store->status = $status;
            $store->option = $option;
            $store->published_at = $published_at;
            $store->save();

            $post_category = new PostCategory;
            $post_category->post_id = $store->id;
            $post_category->category_id = json_encode($category);
            $post_category->save();

            $post_tag = new PostTag;
            $post_tag->post_id = $store->id;
            $post_tag->tag_id = json_encode($tag_id);
            $post_tag->save();
        });
        return redirect($this->prefix.'posts')->with(['msg' => 'Saved', 'status' => 'success']);
    }

    /**
     * Show the form for editing post.
     * @param $id
     * @return Response
     */
    public function edit_post($id)
    {
        $page_meta_title = 'Posts';
        $act = 'Edit';
        $action = $this->prefix.'update-post/'.$id;
        $post = Posts::where('id', $id)->first();
        if (isset($post)) {
            $title = $post->title;
            $body = $post->body;
            $alltag = Tag::get();
            $selected_tag = PostHelper::get_post_tag($post->id, 'id');

            $featured_img = $post->featured_img;
            $media = Media::orderBy('created_at','desc')->get();
            $option = json_decode($post->option);
            $files = $option->files;
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $post->status;
            $published_at = $post->published_at;
            $item_id = $post->id;

            return view('blog::admin.post_form')->with(['item_id' => $item_id, 'page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'post' => $post , 'title' => $title, 'body' => $body, 'alltag' => $alltag, 'selected_tag' => $selected_tag, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'files' => $files]);
        } else {
            return redirect($this->prefix.'posts')->with(['msg' => 'Post Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified post in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_post(Request $request, $id)
    {
        $title = $request->input('title');
        $body = $request->input('body');
        $category = $request->get('category');
        $tag = $request->input('tag');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $file_doc = $request->input('file_doc');
        $file_label = $request->input('file_label');
        $option['files'] = '';
        if (isset($file_doc)) {
            for ($i=0; $i < count($file_doc); $i++) { 
                $option['files'][$i]['file_label'] = $file_label[$i];
                $option['files'][$i]['file_doc'] = $file_doc[$i];
            }
        }
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        DB::transaction(function() use ($id, $title, $category, $tag, $body, $featured_img, $option, $status, $published_at) {
            if (isset($tag)) {
                // save tag to table tag
                $tag_id = array();
                foreach ($tag as $key) {
                    $tag_slug = PostHelper::make_slug($key);
                    $check = Tag::where('slug', $tag_slug)->first();
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

            $update = Posts::where('id', $id)->first();
            $update->title = $title;
            $update->body = $body;
            $update->featured_img = $featured_img;
            $update->author = 1;
            $update->status = $status;
            $update->option = $option;
            $update->published_at = $published_at;
            $update->update();

            $post_category = PostCategory::where('post_id', $id)->first();
            $post_category->post_id = $update->id;
            $post_category->category_id = json_encode($category);
            $post_category->update();

            $post_tag = PostTag::where('post_id', $id)->first();
            $post_tag->post_id = $update->id;
            $post_tag->tag_id = json_encode($tag_id);
            $post_tag->update();
        });
        return redirect($this->prefix.'edit-post/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);

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
     * Store a file in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_file(Request $req){
        $this->validate($req, [
            'fileUpload.*' => 'mimes:pdf,doc,docx,xlsx,xml,txt',
        ]);
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
                    // $fileNames[];
                }
                echo json_encode($fileNames);
            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                
            }
        }else{
            return  "Error adding file";
        }
    }

    /**
     * Remove the specified file from storage.
     * @param $id
     * @return Response
     */
    public function destroy_file($postId, $fileName){
        if(Storage::disk('s3')->exists('files/'.$fileName)){
            $this->PostHelper->deleteFile($fileName, 'files');
            if ($postId != 0) {
                $post = Posts::where('id', $postId)->first();
                $option = json_decode($post->option);
                if ($option->files != '') {
                    $newFiles = '';
                    foreach ($option->files as $file) {
                        if ($file->file_doc != $fileName) {
                            $newFiles[]['file_doc'] = $file->file_doc;
                            $newFiles[]['file_label'] = $file->file_label;                        
                        }
                    }
                    if ($newFiles == '') {
                        $option->files = '';    
                    } else {
                        $option->files = json_encode($newFiles);
                    }
                }
                $post->update();
            }
            return "File deleted";
        }else{
            return "File not found";
        }
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
            return redirect(route('tags'))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('tags'))->with(['msg' => 'Save Error', 'status' => 'danger']);
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
            return redirect($this->prefix.'tag')->with('msg', 'Tag Not Found')->with('status', 'danger');
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
            return redirect(route('tags'))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('tags'))->with(['msg' => 'Save Error', 'status' => 'danger']);
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
        return redirect(route('tags'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
                return response("<li><label><input selected name='categories[]' type='checkbox' value='$store->id'>$store->name</label></li>");
            }

            return redirect(route('categories'))->with(['msg' => 'Saved', 'status' => 'success']);

        } else {
            return redirect(route('categories'))->with(['msg' => 'Save Error', 'status' => 'danger']);
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
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger']);
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
        $update->description = $request->input('description');
        $update->parent = $parent;
        if ($update->save()){
            return redirect(route('categories'))->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect(route('categories'))->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect(route('categories'));
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
        return redirect(route('categories'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
        $store = new Category;
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
        $update = Category::where('id', $id)->first();
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
        return redirect($this->prefix.'category')->with(['msg' => 'Delete Success', 'status' => 'success']);
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
        $page_meta_title = 'Tag';
        $act = 'Edit';
        $action = $this->prefix.'update-tag/'.$id;
        $tag = Tag::where('id', $id)->first();
        if (isset($tag)) {
            $name = $tag->name;
            return view('blog::admin.tag_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'tag' => $tag, 'name' => $name]);
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
        $update = Tag::where('id', $id)->first();
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
        return redirect($this->prefix.'tag')->with(['msg' => 'Delete Success', 'status' => 'success']);
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
            'media.*' => 'image|max:3000',
        ]);
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
        $medias = Media::where('id',$id)->get();
        if($medias[0]->name != ""){
            if(Storage::disk('s3')->exists('shbtm/media/'.$medias[0]->name)){
                $this->PostHelper->deleteImage($medias[0]->name, 'media');
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
                return redirect($this->prefix.'media')->with(['msg' => 'Delete Error. Media Not Found', 'status' => 'danger']);
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
        $page = Page::where('slug', $slug)->first();
        if (isset($page)) {
            return view('blog::admin.single_page')->with(['page_meta_title' => $page_meta_title, 'page' => $page]);
        } else {
            return redirect($this->prefix.'pages')->with('msg', 'Page Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get pages for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_pages(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Page::orderBy($col,$direction);
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
     * Show the form for creating a new page.
     * @return Response
     */
    public function create_page()
    {
        $page_meta_title = 'Page';
        $act = 'New';
        $action = $this->prefix.'store-page';
        $title = '';
        $body = '';
        $featured_img = '';
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';

        $media = Media::orderBy('created_at','desc')->get();
        return view('blog::admin.page_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'body' => $body, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at]);
    }

    /**
     * Store a newly created page in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_page(Request $request)
    {
        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $body = $request->input('body');
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

        $slug_check = Posts::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        $store = new Page;
        $store->title = $title;
        $store->slug = $slug;
        $store->body = $body;
        $store->featured_img = $featured_img;
        $store->author = 1;
        $store->status = $status;
        $store->option = $option;
        $store->published_at = $published_at;
        if ($store->save()) {
            return redirect($this->prefix.'edit-page/'.$store->id)->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'pages')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Show the form for editing the specified page.
     * @param $id
     * @return Response
     */
    public function edit_page($id)
    {
        $page_meta_title = 'Page';
        $act = 'Edit';
        $action = $this->prefix.'update-page/'.$id;
        $page = Page::where('id', $id)->first();
        if (isset($page)) {
            $title = $page->title;
            $body = $page->body;

            $featured_img = $page->featured_img;
            $option = json_decode($page->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $page->status;
            $published_at = $page->published_at;

            $media = Media::orderBy('created_at','desc')->get();

            return view('blog::admin.page_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'page' => $page , 'title' => $title, 'body' => $body, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at]);
        } else {
            return redirect($this->prefix.'pages')->with(['msg' => 'Page Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified page in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_page(Request $request, $id)
    {
        $title = $request->input('title');
        $body = $request->input('body');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        $update = Page::where('id', $id)->first();
        $update->title = $title;
        $update->body = $body;
        $update->featured_img = $featured_img;
        $update->status = $status;
        $update->option = $option;
        $update->published_at = $published_at;

        if ( $update->update()) {
            return redirect($this->prefix.'edit-page/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'edit-page/'.$id)->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Remove the specified page from storage.
     * @param $id
     * @return Response
     */
    public function destroy_page($id)
    {
        $page = Page::where('id', $id)->first();
        if (isset($page)) {
            if ($page->delete()) {
                return redirect($this->prefix.'pages')->with(['msg' => 'Deleted', 'status' => 'success']);
            } else {
                return redirect($this->prefix.'pages')->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        } else {
            return redirect($this->prefix.'pages')->with(['msg' => 'Page Not Found', 'status' => 'danger']);
        }

    }

    /**
     * Remove multiple page from storage.
     * @param  Request $request
     * @return Response
     */
    public function bulk_delete_page(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $page = Page::where('id', $id)->first();
            if (isset($page)) {
                if ($page->delete()) {
                    // do nothing
                } else {
                    return redirect($this->prefix.'pages')->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect($this->prefix.'pages')->with(['msg' => 'Delete Error. Page Not Found', 'status' => 'danger']);
            }
        }
        return redirect($this->prefix.'pages')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }
    // end page controller

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
}
