<?php
namespace Modules\Event\Http\Helpers;

use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Blog\Entities\Categories;
use Modules\Event\Entities\EventCategoryRelation;
use Modules\Event\Entities\EventForumRelation;
use Modules\Event\Entities\EventMentorRelation;
use Modules\Blog\Http\Helpers\PostHelper;
use Illuminate\Http\File;
use Image;
use DB;

use Modules\Blog\Entities\Posts;

class EventHelper
{
    private $prefix;

    public function __construct(){
        $this->prefix = 'admin/blog/';
    }
    
    /**
     * Get all category for list on event form.
     * @param  $event_id
     * @return Response
     */
    public static function get_list_category($categories=[0]){

        $maincategory = Categories::get(); 
        $allcategory = '';
        $selected_cat = [];
        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $categories) ? 'checked' : '';
            $allcategory .= '<li><label><input '.$selected.' name="categories[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label>';
            $allcategory .= '</li>';
        }
        return $allcategory;
    }

    /**
     * Get event category.
     * @param  $event_id
     * @return Response
     */
    public static function get_event_category($event_id, $select = ''){
        $selected_cat = array();
        $event_category = EventCategoryRelation::where('event_id', $event_id)->first();
        $selected_cat_id = json_decode($event_category->category_id);

        if (count($selected_cat_id) > 0) {
            foreach ($selected_cat_id as $key) {
                if ($select != '') {
                    $category = Categories::where('id', $key)->first()->$select;
                } else {
                    $category = Categories::where('id', $key)->first();
                }
                $selected_cat[] = $category;
            }   
        }

        return $selected_cat;
    }
    
    /**
     * Get event forum.
     * @param  $event_id
     * @return Response
     */
    public static function get_event_forum($event_id){
        $event_forum = EventForumRelation::where('event_id', $event_id)->first();

        return $event_forum->forum_id;
    }

    /**
     * Get event mentor.
     * @param  $event_id
     * @return Response
     */
    public static function get_event_mentor($event_id){
        $event_mentor = EventMentorRelation::where('event_id', $event_id)->first();

        return $event_mentor->mentor_id;
    }

    /**
     * Delete event.
     * @param  $id, $is_bulk
     * @return Response
     */
    public static function delete_event($id, $is_bulk = ''){
        $event = Posts::where('id', $id)->first();
        if ($event) {
            DB::beginTransaction();
            try {
                $event->deleted = 1;
                $event->save();  
                
                PostHelper::clear_all();
                DB::commit();
                if ($is_bulk == 'bulk') {
                    // all good. do nothing
                } else {
                    return redirect(route('panel.event__index'))->with(['msg' => 'Deleted', 'status' => 'success'])->send();    
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('panel.event__index'))->with(['msg' => 'Delete Error', 'status' => 'danger'])->send();
            }
        } else {
            return redirect(route('panel.event__index'))->with(['msg' => 'event Not Found', 'status' => 'danger'])->send();
        }
    }

    /**
     * delete category.
     * @param  $id
     * @return Response
     */
    public static function delete_category($id, $is_bulk = ''){
        $category = Categories::where('id', $id)->first();
        if (isset($category)) {
            DB::beginTransaction();
            try {
                $event_category = EventCategoryRelation::where('category_id', 'like', '%'.$id.'%')->get();
                foreach ($event_category as $event) {
                    $category_id = json_decode($event->category_id);
                    $newcat = '';
                    foreach ($category_id as $cat) {
                        if ($cat != $id) {
                            $newcat[] = $cat;
                        }
                    }
                    if ($newcat == '') {
                        $event->category_id = '';    
                    } else {
                        $event->category_id = json_encode($newcat);
                    }
                    $event->update();
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

}
?>