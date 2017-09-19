<?php
namespace Modules\Blog\Http\Helpers;

use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\PostCategory;
use Illuminate\Http\File;

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
       
        return 'https://s3-'.$region.'.amazonaws.com/'.$bucket.'/'.$path.'/'.$url;
    }

    public static function putFile($file, $path, $fileName){
        $s3 = \Storage::disk('s3');
        $filePath = '/'.$path.'/'.$fileName;
        // $s3->put($filePath, $file, 'public');
        $s3->putFileAs('files',new File($file),$fileName, 'public');

    }

    public static function deleteFile($file, $path){
        $s3 = \Storage::disk('s3');
        $filePath = '/'.$path.'/' . $file;
        $s3->delete($filePath);
    }
}
?>