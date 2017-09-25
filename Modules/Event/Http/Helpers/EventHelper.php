<?php
namespace Modules\Event\Http\Helpers;

use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventCategoryRelation;
use Modules\Event\Entities\EventForumRelation;
use Modules\Event\Entities\EventMentorRelation;
use Illuminate\Http\File;
use Image;

class EventHelper
{
    /**
     * Get all category for list on event form.
     * @param  $event_id
     * @return Response
     */
    public static function get_list_category($event_id = ''){
        $maincategory = EventCategory::get(); 
        $allcategory = '';
        $selected_cat = array();

        if ($event_id > 0) {
            $eventCategory = EventCategoryRelation::where('event_id', $event_id)->first();
            $selected_cat_id = json_decode($eventCategory->category_id);

            if (count($selected_cat_id) > 0) {
                foreach ($selected_cat_id as $key) {
                    $category = EventCategory::where('id', $key)->first()->id;
                    $selected_cat[] = $category;
                }   
            }
        } 

        foreach ($maincategory as $main) {
            $selected = in_array($main->id, $selected_cat) ? 'checked' : '';
            $allcategory .= '<li><label><input '.$selected.' name="category[]" type="checkbox" value="'.$main->id.'">'.$main->name.'</label>';
            $allcategory .= '</li>';
        }

        return $allcategory;
    }

    /**
     * Get event category.
     * @param  $event_id
     * @return Response
     */
    public static function get_event_category($event_id){
        $maincategory = EventCategory::get(); 
        $allcategory = '';
        $selected_cat = array();

        if ($event_id > 0) {
            $event_category = EventCategoryRelation::where('event_id', $event_id)->first();
            $selected_cat_id = json_decode($event_category->category_id);

            if (count($selected_cat_id) > 0) {
                foreach ($selected_cat_id as $key) {
                    $category = EventCategory::where('id', $key)->first()->id;
                    $selected_cat[] = $category;
                }   
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
}
?>