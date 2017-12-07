<?php
namespace Modules\Blog\Http\Helpers;

use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\PostCategory;
use Modules\Blog\Entities\Tags;
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
        $maincategory = Categories::where('parent', null)->get(); 
        $allparent = '';
        $category_parent = '';
        $allparent .= '<option value="none">None</option>';

        if ($category_id > 0) {
            $maincategory = Categories::where('parent', null)->where('id', '!=', $category_id)->get(); 
            $category = Categories::where('id', $category_id)->first();
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
        $selected_cat = [];
        $maincategory = Categories::where('parent', null)->get(); 
        $allcategory = '';

        if ($post_id > 0) {
            $selected_cat = PostHelper::get_post_category($post_id, 'id');
        } 

        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $selected_cat) ? 'checked' : '';
            $allcategory .= '<li><label><input '.$selected.' name="categories[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label><ul>';
            $subcategory = Categories::where('parent', $main->id)->get(); 
            foreach ($subcategory as $sub) {
                $selected = in_array($sub->id, $selected_cat) ? 'checked' : '';
                $allcategory .= '<li><label><input '.$selected.' name="categories[]" type="checkbox" value="'.$sub->id.'">'.$sub->name.'</label></li>';
            }
            $allcategory .= '</ul></li>';
        }

        return $allcategory;
    }

    /**
     * Get all post categories.
     * @param  $post_id
     * @return Response
     */
    public static function get_post_category($post_id, $select = ''){
        $categories_metas = PostMeta::where('post_id',$post_id)->where('key', 'category')->get();
        $categories = [];
        if (count($categories_metas) > 0) {
            foreach ($categories_metas as $meta) {
                if ($select != '') {
                    $get = Categories::where('id', $meta->value)->first()->$select;   
                } else {
                    $get = Categories::where('id', $meta->value)->first();   
                }
                $categories[] = $get;
            }
        }

        return $categories;
    }

    /**
     * Get all post tags.
     * @param  $post_id
     * @return Response
     */
    public static function get_post_tag($post_id, $select = '' ){
        $tag_metas = PostMeta::where('post_id',$post_id)->where('key', 'tag')->get();
        $tags = [];
        if (count($tag_metas) > 0) {
            foreach ($tag_metas as $tag_meta) {
                if ($select != '') {
                    $get = Tags::where('id', $tag_meta->value)->first()->$select;   
                } else {
                    $get = Tags::where('id', $tag_meta->value)->first();   
                }
                $tags[] = $get;
            }
        }

        return $tags;
    }

    /**
     * Check post tags input.
     * @param  $tag_input
     * @return value
     */
    public static function check_tags_input($tag_input){
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

        return $tags;
    }

    /**
     * Save post meta tags helper
     * @param  $post_id, $new_tags
     * @return Response
     */
    public static function save_post_meta_tag($post_id, $new_tags){
        $tag_metas = PostMeta::where('post_id',$post_id)->where('key', 'tag')->get();
        $old_tags = [];
        foreach ($tag_metas as $tag_meta) {
            $old_tags[] = $tag_meta->value;
        }

        $update_tags = array_diff($new_tags, $old_tags);
        $delete_tags = array_diff($old_tags, $new_tags);

        foreach ($delete_tags as $tag_id) {
            $tag = PostMeta::where('post_id',$post_id)->where('key', 'tag')->where('value', $tag_id)->first();
            if (isset($tag)) {
                $tag->delete();
            }
        }

        foreach ($update_tags as $tag_id) {
            PostMeta::insert(['post_id'=>$post_id,'key' => 'tag', 'value'=>$tag_id]);
        }
    }

    /**
     * Save post meta categories helper
     * @param  $post_id, $new_tags
     * @return Response
     */
    public static function save_post_meta_category($post_id, $new_categories){
        $cat_metas = PostMeta::where('post_id',$post_id)->where('key', 'category')->get();
        $old_categories = [];
        foreach ($cat_metas as $cat_meta) {
            $old_categories[] = $cat_meta->value;
        }

        $update_categories = array_diff($new_categories, $old_categories);
        $delete_categories = array_diff($old_categories, $new_categories);

        foreach ($delete_categories as $cat_id) {
            $cat = PostMeta::where('post_id',$post_id)->where('key', 'category')->where('value', $cat_id)->first();
            if (isset($cat)) {
                $cat->delete();
            }
        }

        foreach ($update_categories as $cat_id) {
            PostMeta::insert(['post_id'=>$post_id,'key' => 'category', 'value'=>$cat_id]);
        }
    }

    /**
     * Get full link file
     * @param  $url, $path
     * @return Response
     */
    public static function getLinkFile($url, $path){
        $region = config('filesystems.disks')['s3']['region'];
        $bucket = config('filesystems.disks')['s3']['bucket'];
       
        return 'https://s3-'.$region.'.amazonaws.com/'.$bucket.'/shbtmdev/'.$path.'/'.$url;
    }

    /**
     * Store file to s3
     * @param  $file, $path, $fileName
     * @return Response
     */
    public static function putFile($file, $path, $fileName){
        $s3 = \Storage::disk('s3');
        $s3->putFileAs('/shbtmdev/'.$path, new File($file), $fileName, 'public');
    }

    /**
     * Delete file from s3.
     * @param  $file, $path
     * @return Response
     */
    public static function deleteFile($file, $path){
        $s3 = \Storage::disk('s3');
        $filePath = '/shbtmdev/'.$path.'/' . $file;
        $s3->delete($filePath);
    }

    /**
     * Get full link media.
     * @param  $url, $path, $size = ''
     * @return Response
     */
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

    /**
     * Store file to s3.
     * @param  $file, $path, $fileName
     * @return Response
     */
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
    
    /**
     * Delete media from s3.
     * @param  $file, $path
     * @return Response
     */
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
                $post_category = PostCategories::where('post_id', $id)->first();
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
        $category = Categories::where('id', $id)->first();
        if (isset($category)) {
            DB::beginTransaction();
            try {
                // $post_category = PostCategories::where('category_id', 'like', '%'.$id.'%')->get();
                // foreach ($post_category as $post) {
                //     $category_id = json_decode($post->category_id);
                //     $newcat = '';
                //     foreach ($category_id as $n) {
                //         if ($n != $id) {
                //             $newcat[] = $n;
                //         }
                //     }
                //     if ($newcat == '') {
                //         $post->category_id = '';    
                //     } else {
                //         $post->category_id = json_encode($newcat);
                //     }
                //     $post->update();
                // }

                $children = Categories::where('parent', $id)->get();
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
                    return redirect(route('categories'))->with(['msg' => 'Deleted', 'status' => 'success'])->send();
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('categories'))->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        }else {
            return redirect(route('categories'))->with(['msg' => 'Category Not Found', 'status' => 'danger'])->send();
        }
    }

    /**
     * delete tag.
     * @param  $id
     * @return Response
     */
    public function delete_tag($id, $is_bulk = ''){
        $tag = Tags::where('id', $id)->first();
        if (isset($tag)) {
            DB::beginTransaction();
            try {
                // $post_tag = Tags::where('tag_id', 'like', '%'.$id.'%')->get();
                // foreach ($post_tag as $post) {
                //     $tag_id = json_decode($post->tag_id);
                //     $newcat = '';
                //     foreach ($tag_id as $n) {
                //         if ($n != $id) {
                //             $newcat[] = $n;
                //         }
                //     }
                //     if ($newcat == '') {
                //         $post->tag_id = '';    
                //     } else {
                //         $post->tag_id = json_encode($newcat);
                //     }
                //     $post->update();
                // }
                $tag->delete();

                DB::commit();
                if ($is_bulk == 'bulk') {
                    // do nothing
                } else {
                    return redirect(route('tags'))->with(['msg' => 'Deleted', 'status' => 'success'])->send();
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('tags'))->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        }else {
            return redirect(route('tags'))->with(['msg' => 'Tag Not Found', 'status' => 'danger'])->send();
        }
    }

    public static function get_post_meta($id){
        $post_metas = PostMeta::where('post_id',$id)->get();

        $metas = new \stdClass;;
        foreach ($post_metas as $key => $value) {
            $metas->{$value->key} = $value->value;
        }

        // var_dump($metas);
        // die();

        $data = [];

        $data['event_type']   = $metas->event_type ?? '';
        $data['event_url']    = $metas->event_url ?? '';
        $data['gmaps_url']    = $metas->gmaps_url ?? '';
        $data['location']     = $metas->location ?? '';
        $data['htm']          = $metas->htm ?? '';
        $data['open_at']      = $metas->open_at ?? '';
        $data['closed_at']    = $metas->closed_at ?? '';
        $data['meta_desc']    = $metas->meta_desc ?? '';
        $data['meta_title']   = $metas->meta_title ?? '';
        $data['meta_keyword'] = $metas->meta_keyword ?? '';
        $data['mentors']      = json_decode($metas->event_mentor ?? '') ?? [];

        return $data;
    }
}
?>