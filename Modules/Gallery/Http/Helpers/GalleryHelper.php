<?php
namespace Modules\Gallery\Http\Helpers;

use Modules\Gallery\Entities\Gallery;
use Modules\Gallery\Entities\GalleryCategory;
use Modules\Gallery\Entities\GalleryCategoryRelation;
use Modules\Gallery\Entities\GalleryTag;
use Modules\Gallery\Entities\GalleryTagRelation;
use Illuminate\Http\File;
use Image;
use DB;

class GalleryHelper
{
    private $prefix;

    public function __construct(){
        $this->prefix = 'admin/blog/gallery/';
    }

    /**
     * Get all category parent to select category parent.
     * @param  $category_id
     * @return Response
     */
    public static function get_category_parent($category_id = ''){
        $maincategory = GalleryCategory::where('parent', null)->get(); 
        $allparent = '';
        $category_parent = '';
        $allparent .= '<option value="none">None</option>';

        if ($category_id > 0) {
            $category = GalleryCategory::where('id', $category_id)->first();
            $category_parent = $category->parent;

        }

        foreach ($maincategory as $main) {   
            $selected = $main->id == $category_parent ? 'selected' : '';
            $allparent .= '<option '.$selected.' value="'.$main->id.'">'.$main->name.'</option>';
        }
        
        return $allparent;
    }

    /**
     * Get all category for list on gallery form.
     * @param  $gallery_id
     * @return Response
     */
    public static function get_all_category($gallery_id = ''){
        $maincategory = GalleryCategory::where('parent', null)->get(); 
        $allcategory = '';
        $selected_cat = array();

        if ($gallery_id > 0) {
            $galleryCategory = GalleryCategoryRelation::where('gallery_id', $gallery_id)->first();
            $selected_cat_id = json_decode($galleryCategory->category_id);

            if (count($selected_cat_id) > 0) {
                foreach ($selected_cat_id as $key) {
                    $category = GalleryCategory::where('id', $key)->first()->id;
                    $selected_cat[] = $category;
                }   
            }
        } 

        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $selected_cat) ? 'checked="checked"' : '';
            $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label><ul>';
            $subcategory = GalleryCategory::where('parent', $main->id)->get(); 
            foreach ($subcategory as $sub) {
                $selected = in_array($sub->id, $selected_cat) ? 'checked' : '';
                $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$sub->id.'">'.$sub->name.'</label></li>';
            }
            $allcategory .= '</ul></li>';
        }

        return $allcategory;
    }

    /**
     * Delete gallery.
     * @param  $id, $is_bulk
     * @return Response
     */
    public function delete_gallery($id, $is_bulk = ''){
        $gallery = Gallery::where('id', $id)->first();
        if (isset($gallery)) {
            DB::beginTransaction();
            try {
                $gallery_category = GalleryCategoryRelation::where('gallery_id', $id)->first();
                $gallery_tag = GalleryTagRelation::where('gallery_id', $id)->first();
                $gallery_category->delete();       
                $gallery_tag->delete();   
                $gallery->delete();  
                
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
            return redirect($this->prefix)->with(['msg' => 'gallery Not Found', 'status' => 'danger'])->send();
        }
    }
    
    /**
     * delete category.
     * @param  $id
     * @return Response
     */
    public function delete_category($id, $is_bulk = ''){
        $category = GalleryCategory::where('id', $id)->first();
        if (isset($category)) {
            DB::beginTransaction();
            try {
                $gallery_category = GalleryCategoryRelation::where('category_id', 'like', '%'.$id.'%')->get();
                foreach ($gallery_category as $gallery) {
                    $category_id = json_decode($gallery->category_id);
                    $newcat = '';
                    foreach ($category_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $gallery->category_id = '';    
                    } else {
                        $gallery->category_id = json_encode($newcat);
                    }
                    $gallery->update();
                }

                $children = GalleryCategory::where('parent', $id)->get();
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
        $tag = GalleryTag::where('id', $id)->first();
        if (isset($tag)) {
            DB::beginTransaction();
            try {
                $gallery_tag = GalleryTagRelation::where('tag_id', 'like', '%'.$id.'%')->get();
                foreach ($gallery_tag as $gallery) {
                    $tag_id = json_decode($gallery->tag_id);
                    $newcat = '';
                    foreach ($tag_id as $n) {
                        if ($n != $id) {
                            $newcat[] = $n;
                        }
                    }
                    if ($newcat == '') {
                        $gallery->tag_id = '';    
                    } else {
                        $gallery->tag_id = json_encode($newcat);
                    }
                    $gallery->update();
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