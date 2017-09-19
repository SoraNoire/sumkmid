<?php
namespace Modules\Blog\Http\Helpers;

use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\PostCategory;
use Illuminate\Http\File;
use Image;

class PostHelper
{
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
        $s3->putFileAs('files',new File($file),$fileName, 'public');

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
        var_dump($s3->files('/shbtm/media'));

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
}
?>