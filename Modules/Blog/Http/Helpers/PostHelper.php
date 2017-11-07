<?php
namespace Modules\Blog\Http\Helpers;

use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\Tag;
use Modules\Blog\Entities\PostTag;
use Illuminate\Http\File;
use Image;
use DB;

class PostHelper
{
    private $prefix;

    public function __construct(){
        $this->prefix = 'admin/blog/';
    }
    
	/**
     * Make slug.
     * @param  $string
     * @return Response
     */
    public static function make_slug($string){
        $slug_title = str_replace('(', '', $string);
        $slug_title = str_replace(')', '', $slug_title);
        $slug_title = str_replace("'", '', $slug_title);
        $slug_title = str_replace(":", '', $slug_title);
        $slug_title = str_replace(".", ' ', $slug_title);
        $slug_title = str_replace("&", '', $slug_title);
        $slug_title = str_replace(",", '', $slug_title);
        $slug_title = str_replace("`", '', $slug_title);
        $slug_title = str_replace("?", '', $slug_title);
        $slug_title = str_replace('"', '', $slug_title);
        $the_slug = str_replace(' ', '-', $slug_title);   
        $the_slug = strtolower($the_slug);
        return $the_slug;
    }

    /**
     * Get all category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
        $maincategory = Category::where('parent', null)->get(); 
        $allparent = '';
        $category_parent = '';
        $allparent .= '<option value="none">None</option>';

        if ($category_id > 0) {
            $category = Category::where('id', $category_id)->first();
            $category_parent = $category->parent;

        }

        foreach ($maincategory as $main) {   
            $selected = $main->id == $category_parent ? 'selected' : '';
            $allparent .= '<option '.$selected.' value="'.$main->id.'">'.$main->name.'</option>';
        }

        return $allparent;
    }

    /**
     * Get all category for list on post form.
     * @param  $post_id
     * @return Response
     */
    public static function get_all_category($post_id = ''){
        $maincategory = Category::where('parent', null)->get(); 
        $allcategory = '';
        $selected_cat = array();

        if ($post_id > 0) {
            $PostCategory = PostCategory::where('post_id', $post_id)->first();
            $selected_cat_id = json_decode($PostCategory->category_id);

            if (count($selected_cat_id) > 0) {
                foreach ($selected_cat_id as $key) {
                    $category = Category::where('id', $key)->first()->id;
                    $selected_cat[] = $category;
                }   
            }
        } 

        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $selected_cat) ? 'checked' : '';
            $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label><ul>';
            $subcategory = Category::where('parent', $main->id)->get(); 
            foreach ($subcategory as $sub) {
                $selected = in_array($sub->id, $selected_cat) ? 'selected' : '';
                $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$sub->id.'">'.$sub->name.'</label></li>';
            }
            $allcategory .= '</ul></li>';
        }

        return $allcategory;
    }

    public static function getLinkFile($url, $path){
        $region = config('filesystems.disks')['s3']['region'];
        $bucket = config('filesystems.disks')['s3']['bucket'];
       
        return 'https://s3-'.$region.'.amazonaws.com/'.$bucket.'/shbtm/'.$path.'/'.$url;
    }

    public static function putFile($file, $path, $fileName){
        $s3 = \Storage::disk('s3');
        $s3->putFileAs('/shbtm/'.$path, new File($file), $fileName, 'public');
    }

    public static function deleteFile($file, $path){
        $s3 = \Storage::disk('s3');
        $filePath = '/shbtm/'.$path.'/' . $file;
        $s3->delete($filePath);
    }

    public static function getLinkImage($url, $path, $size = ''){
        $region = config('filesystems.disks')['s3']['region'];
        $bucket = config('filesystems.disks')['s3']['bucket'];
        // if ($size != '') {
        //     if ($size == 'thumbnail') {
        //         $size = 300;
        //     } else if ($size == 'medium') {
        //         $size = 800;
        //     } else if ($size == 'large') {
        //         $size = 1200;
        //     }
        //     $name = explode('.', $url);
        //     $imageUrl = $name[0].'-'.$size.'.'.$name[1];
        // } else {
            $imageUrl = $url;
        // }

        return 'https://s3-'.$region.'.amazonaws.com/'.$bucket.'/shbtm/'.$path.'/'.$imageUrl;
    }

    public static function putImage($file, $path, $fileName){
        $imgObject = Image::make($file);
        $ext = $file->getClientOriginalExtension();
        $large = 1200;
        $medium = 800;
        $thumb = 300;

        $img = $imgObject;
        $img = $img->stream($file->getClientOriginalExtension(), 90);

        // $imgLarge = $imgObject;
        // $imgLarge->resize($large, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $imgLarge = $imgLarge->stream($file->getClientOriginalExtension(), 90);

        // $imgMedium = $imgObject;
        // $imgMedium->resize($medium, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $imgMedium = $imgMedium->stream($file->getClientOriginalExtension(), 90);

        // $imgThumb = $imgObject;
        // $imgThumb->resize($thumb, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $imgThumb = $imgThumb->stream($file->getClientOriginalExtension(), 90);

        $s3 = \Storage::disk('s3');
        $filePath = '/shbtm/'.$path.'/' . $fileName.'.'.$ext;
        $s3->put($filePath, $img->__toString(), 'public');

        // $filePath = '/'.$path.'/' . $fileName. '-'. $large.'.'.$ext;
        // $s3->put($filePath, $imgLarge->__toString(), 'public');

        // $filePath = '/'.$path.'/' . $fileName. '-'. $medium.'.'.$ext;
        // $s3->put($filePath, $imgMedium->__toString(), 'public');

        // $filePath = '/'.$path.'/' . $fileName. '-'. $thumb.'.'.$ext;
        // $s3->put($filePath, $imgThumb->__toString(), 'public');
    }

    public static function deleteImage($file, $path){
        $large = 1200;
        $medium = 800;
        $thumb = 300;
        $ex = explode('.', $file);
        $s3 = \Storage::disk('s3');

        $filePath = '/shbtm/'.$path.'/' . $file;
        $s3->delete($filePath);

        // $filePath = '/'.$path.'/' . $ex[0].'-'.$thumb.'.'.$ex[1];
        // $s3->delete($filePath);

        // $filePath = '/'.$path.'/' . $ex[0].'-'.$medium.'.'.$ex[1];
        // $s3->delete($filePath);

        // $filePath = '/'.$path.'/' . $ex[0].'-'.$large.'.'.$ex[1];
        // $s3->delete($filePath);
    }

    /**
     * Delete post.
     * @param  $id, $is_bulk
     * @return Response
     */
    public function delete_post($id, $is_bulk = ''){
        $post = Posts::where('id', $id)->first();
        if (isset($post)) {
            DB::beginTransaction();
            try {
                $post_category = PostCategory::where('post_id', $id)->first();
                $post_tag = PostTag::where('post_id', $id)->first();
                $post_category->delete();       
                $post_tag->delete();   
                $post->delete();  
                
                DB::commit();
                if ($is_bulk == 'bulk') {
                    // all good. do nothing
                } else {
                    return redirect($this->prefix)->with(['msg' => 'Deleted', 'status' => 'success'])->send();    
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix)->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        } else {
            return redirect($this->prefix)->with(['msg' => 'post Not Found', 'status' => 'danger'])->send();
        }
    }
    
    /**
     * delete category.
     * @param  $id
     * @return Response
     */
    public function delete_category($id, $is_bulk = ''){
        $category = Category::where('id', $id)->first();
        if (isset($category)) {
            DB::beginTransaction();
            try {
                $post_category = PostCategory::where('category_id', 'like', '%'.$id.'%')->get();
                foreach ($post_category as $post) {
                    $category_id = json_decode($post->category_id);
                    $newcat = '';
                    foreach ($category_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $post->category_id = '';    
                    } else {
                        $post->category_id = json_encode($newcat);
                    }
                    $post->update();
                }

                $children = Category::where('parent', $id)->get();
                if (count($children) > 0) {
                    foreach ($children as $child) {
                        $child->parent = null;
                        $child->update();
                    }
                }

                $category->delete();

                DB::commit();
                if ($is_bulk == 'bulk') {
                    // do nothing
                } else {
                    return redirect($this->prefix.'category')->with(['msg' => 'Deleted', 'status' => 'success'])->send();
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix.'category')->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        }else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger'])->send();
        }
    }

    /**
     * delete tag.
     * @param  $id
     * @return Response
     */
    public function delete_tag($id, $is_bulk = ''){
        $tag = PostTag::where('id', $id)->first();
        if (isset($tag)) {
            DB::beginTransaction();
            try {
                $post_tag = PostTag::where('tag_id', 'like', '%'.$id.'%')->get();
                foreach ($post_tag as $post) {
                    $tag_id = json_decode($post->tag_id);
                    $newcat = '';
                    foreach ($tag_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $post->tag_id = '';    
                    } else {
                        $post->tag_id = json_encode($newcat);
                    }
                    $post->update();
                }
                $tag->delete();

                DB::commit();
                if ($is_bulk == 'bulk') {
                    // do nothing
                } else {
                    return redirect($this->prefix.'tag')->with(['msg' => 'Deleted', 'status' => 'success'])->send();
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix.'tag')->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        }else {
            return redirect($this->prefix.'tag')->with(['msg' => 'Tag Not Found', 'status' => 'danger'])->send();
        }
    }
}
?>