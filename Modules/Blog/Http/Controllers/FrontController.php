<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Blog\Entities\Page;
use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Tag;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\PostTag;
use Modules\Blog\Entities\Media;
use Carbon\Carbon;

class FrontController extends Controller{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    // post controller
    public function index(){
    	$meta_title = 'Home';
      return view('blog::pages.blog.index')->with(['meta_title' => $meta_title]);
    }
    public function single_post($slug){
      $post = Posts::where('slug', $slug)->first();
      $post->published_at = date('d F Y', strtotime($post->published_at));
      $meta_title = 'Berita '.$post->title;
      if (isset($post)) {
          $PostTag = json_decode(PostTag::where('post_id', $post->id)->first()->tag_id);
          $tag = array();
          if (count($tag) > 0) {
              foreach ($PostTag as $PostTag) {
                  $tag[] = tag::where('id', $PostTag)->first();
              }
          }
          $PostCategory = json_decode(PostCategory::where('post_id', $post->id)->first()->category_id);
          $category = array();
          if (count($tag) > 0) {
              foreach ($PostCategory as $PostCategory) {
                  $category[] = Category::where('id', $PostCategory)->first();
              }
          }
          return view('blog::pages.blog.single')->with(['meta_title' => $meta_title, 'post' => $post, 'tag' => $tag, 'category' => $category]);
      } else {
          return redirect('/')->with('msg', 'Post Not Found')->with('status', 'danger');
      }
    }
}
