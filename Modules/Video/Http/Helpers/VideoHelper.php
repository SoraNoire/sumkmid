<?php
namespace Modules\Video\Http\Helpers;

use Modules\Video\Entities\Video;
use Modules\Video\Entities\VideoCategory;
use Modules\Video\Entities\VideoCategoryRelation;
use Modules\Video\Entities\VideoTag;
use Modules\Video\Entities\VideoTagRelation;
use Illuminate\Http\File;
use Image;
use DB;

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

    /**
     * Delete video.
     * @param  $id, $is_bulk
     * @return Response
     */
    public static function delete_video($id, $is_bulk){
        $video = Video::where('id', $id)->first();
        if (isset($video)) {
            DB::beginTransaction();
            try {
                $video_category = VideoCategoryRelation::where('video_id', $id)->first();
                $video_tag = VideoTagRelation::where('video_id', $id)->first();
                $video_category->delete();       
                $video_tag->delete();   
                $video->delete();  
                
                DB::commit();
                if ($is_bulk == 'bulk') {
                    // all good. do nothing
                } else {
                    return redirect($this->prefix)->with(['msg' => 'Deleted', 'status' => 'success']);    
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix)->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        } else {
            return redirect($this->prefix)->with(['msg' => 'video Not Found', 'status' => 'danger']);
        }
    }
    
    /**
     * delete category.
     * @param  $id
     * @return Response
     */
    public static function delete_category($id, $is_bulk = ''){
        $category = VideoCategory::where('id', $id)->first();
        if (isset($category)) {
            DB::beginTransaction();
            try {
                $video_category = VideoCategoryRelation::where('category_id', 'like', '%'.$id.'%')->get();
                foreach ($video_category as $video) {
                    $category_id = json_decode($video->category_id);
                    $newcat = '';
                    foreach ($category_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $video->category_id = '';    
                    } else {
                        $video->category_id = json_encode($newcat);
                    }
                    $video->update();
                }

                $children = VideoCategory::where('parent', $id)->get();
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
                    return redirect($this->prefix.'category')->with(['msg' => 'Deleted', 'status' => 'success']);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix.'category')->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        }else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger']);
        }
    }

    /**
     * delete tag.
     * @param  $id
     * @return Response
     */
    public static function delete_tag($id, $is_bulk = ''){
        $tag = VideoTag::where('id', $id)->first();
        if (isset($tag)) {
            DB::beginTransaction();
            try {
                $video_tag = VideoTagRelation::where('tag_id', 'like', '%'.$id.'%')->get();
                foreach ($video_tag as $video) {
                    $tag_id = json_decode($video->tag_id);
                    $newcat = '';
                    foreach ($tag_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $video->tag_id = '';    
                    } else {
                        $video->tag_id = json_encode($newcat);
                    }
                    $video->update();
                }
                $tag->delete();

                DB::commit();
                if ($is_bulk == 'bulk') {
                    // do nothing
                } else {
                    return redirect($this->prefix.'tag')->with(['msg' => 'Deleted', 'status' => 'success']);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect($this->prefix.'tag')->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        }else {
            return redirect($this->prefix.'tag')->with(['msg' => 'Tag Not Found', 'status' => 'danger']);
        }
    }
}
?>