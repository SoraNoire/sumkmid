<?php
namespace Modules\Video\Http\Helpers;

use Modules\Video\Entities\VideoCategory;
use Modules\Video\Entities\VideoCategoryRelation;
use Illuminate\Http\File;
use Image;

class VideoHelper
{
    /**
     * Get all category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
        $maincategory = VideoCategory::where('parent', null)->get(); 
        $allparent = '';
        $category_parent = '';
        $allparent .= '<option value="none">None</option>';

        if ($category_id > 0) {
            $category = VideoCategory::where('id', $category_id)->first();
            $category_parent = $category->parent;

        }

        foreach ($maincategory as $main) {   
            $selected = $main->id == $category_parent ? 'selected' : '';
            $allparent .= '<option '.$selected.' value="'.$main->id.'">'.$main->name.'</option>';
        }

        return $allparent;
    }

    /**
     * Get all category for list on video form.
     * @param  $video_id
     * @return Response
     */
    public static function get_all_category($video_id = ''){
        $maincategory = VideoCategory::where('parent', null)->get(); 
        $allcategory = '';
        $selected_cat = array();

        if ($video_id > 0) {
            $videoCategory = VideoCategoryRelation::where('video_id', $video_id)->first();
            $selected_cat_id = json_decode($videoCategory->category_id);

            if (count($selected_cat_id) > 0) {
                foreach ($selected_cat_id as $key) {
                    $category = VideoCategory::where('id', $key)->first()->id;
                    $selected_cat[] = $category;
                }   
            }
        } 

        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $selected_cat) ? 'checked' : '';
            $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label><ul>';
            $subcategory = VideoCategory::where('parent', $main->id)->get(); 
            foreach ($subcategory as $sub) {
                $selected = in_array($sub->id, $selected_cat) ? 'selected' : '';
                $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$sub->id.'">'.$sub->name.'</label></li>';
            }
            $allcategory .= '</ul></li>';
        }

        return $allcategory;
    }
}
?>