<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventCategoryRelation;
use Modules\Event\Entities\EventForumRelation;
use Modules\Event\Entities\EventMentorRelation;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Event\Http\Helpers\EventHelper;
use Carbon\Carbon;
use DB;
use View;

class EventController extends Controller
{
    public function __construct(){
        $this->EventHelper = new EventHelper;
        $this->prefix = 'admin/blog/event/';
        View::share('prefix', $this->prefix);
    }
    /**
     * Display a listing of event.
     * @return Response
     */
    public function index(){
        $page_meta_title = 'Events';
        return view('event::admin.index')->with(['page_meta_title' => $page_meta_title]);
    }

    /**
     * Show single events.
     * @param  $slug
     * @return Response
     */
    public function show_event($slug){
        $page_meta_title = 'Single Event';
        $event = Event::where('slug', $slug)->first();
        if (isset($event)) {
            $category = EventHelper::get_event_category($event->id);
            $forum = EventHelper::get_event_forum($event->id);
            $mentor = EventHelper::get_event_mentor($event->id);

            $option = json_decode($event->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;

            if ($event->status == '1') {
                $status = 'published';
            } else {
                $status = 'draft';
            }

            return view('event::admin.single')->with(['page_meta_title' => $page_meta_title, 'event' => $event, 'category' => $category, 'forum' => $forum, 'mentor' => $mentor, 'status' => $status, 'meta_desc' => $meta_desc, 'meta_keyword' => $meta_keyword, 'meta_title' => $meta_title]);
        } else {
            return redirect($this->prefix.'events')->with('msg', 'event Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get events for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_events(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Event::orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();
        $output['recordsTotal'] = $query->count();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = 10;

        return $output;
    }

    /**
     * Show the form for creating a new event.
     * @return Response
     */
    public function create_event()
    {
        $page_meta_title = 'Events';
        $act = 'New';
        $action = $this->prefix.'store-event';

        $title = '';
        $description = '';
        $featured_img = '';
        $media = Media::orderBy('created_at','desc')->get();
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';
        $event_type = 'offline';
        $forum_id = '';
        $mentor_id = '';
        $location = '';
        $htm = '';
        $open_at = '';
        $closed_at = '';
        $list_category = EventHelper::get_list_category();

        return view('event::admin.event_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'description' => $description, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'event_type' => $event_type, 'forum_id' => $forum_id, 'mentor_id' => $mentor_id, 'location' => $location, 'htm' => $htm, 'open_at' => $open_at, 'closed_at' => $closed_at, 'list_category' => $list_category]);
    }

    /**
     * Store a newly created event in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_event(Request $request)
    {
        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $description = $request->input('description');
        $featured_img = $request->input('featured_img');
        $event_type = $request->get('event_type');
        $category = $request->get('category');
        $location = $request->input('location');
        $htm = $request->input('htm');
        $author = 1;
        $forum_id = $request->input('forum_id');
        $mentor = $request->input('mentor');
        $status = $request->get('status');
        $event_type = $request->get('event_type');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);
        $open_at = $request->input('open_at');
        $closed_at = $request->input('closed_at');
        $published_at = $request->input('published_at');

        if ($published_at = 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Event::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::beginTransaction();
        try {
            $store = new Event;
            $store->title = $title;
            $store->slug = $slug;
            $store->description = $description;
            $store->featured_img = $featured_img;
            $store->event_type = $event_type;
            $store->location = $location;
            $store->htm = $htm;
            $store->option = $option;
            $store->author = $author;
            $store->status = $status;
            $store->open_at = $open_at;
            $store->closed_at = $closed_at;
            $store->published_at = $published_at;
            $store->save();

            $event_category = new EventCategoryRelation;
            $event_category->event_id = $store->id;
            $event_category->category_id = json_encode($category);
            $event_category->save();

            $event_forum = new EventForumRelation;
            $event_forum->event_id = $store->id;
            $event_forum->forum_id = $forum_id;
            $event_forum->save();

            $event_mentor = new EventMentorRelation;
            $event_mentor->event_id = $store->id;
            $event_mentor->mentor_id = $mentor;
            $event_mentor->save();

            DB::commit();
            return redirect($this->prefix)->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect($this->prefix)->with(['msg' => 'Error Saving '.$e, 'status' => 'danger']);
        }

        
    }

    /**
     * Show the form for editing event.
     * @param $id
     * @return Response
     */
    public function edit_event($id)
    {
        $page_meta_title = 'Events';
        $act = 'Edit';
        $action = $this->prefix.'update-event/'.$id;
        $event = Event::where('id', $id)->first();
        if (isset($event)) {
            $title = $event->title;
            $description = $event->description;
            $featured_img = $event->featured_img;
            $media = Media::orderBy('created_at','desc')->get();           
            $option = json_decode($event->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $event->status;
            $published_at = $event->published_at;
            $event_type = $event->event_type;
            $forum_id = '';
            $mentor_id = '';
            $location = $event->location;
            $htm = $event->htm;
            $open_at = $event->open_at;
            $closed_at = $event->closed_at;
            $list_category = EventHelper::get_list_category($event->id);

            return view('event::admin.event_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'event' => $event , 'title' => $title, 'description' => $description, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'event_type' => $event_type, 'forum_id' => $forum_id, 'mentor_id' => $mentor_id, 'location' => $location, 'htm' => $htm, 'open_at' => $open_at, 'closed_at' => $closed_at, 'list_category' => $list_category]);
        } else {
            return redirect($this->prefix)->with(['msg' => 'Event Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified event in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_event(Request $request, $id)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $featured_img = $request->input('featured_img');
        $event_type = $request->get('event_type');
        $category = $request->get('category');
        $location = $request->input('location');
        $htm = $request->input('htm');
        $author = 1;
        $forum_id = $request->input('forum_id');
        $mentor = $request->input('mentor');
        $status = $request->get('status');
        $event_type = $request->get('event_type');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);
        $open_at = $request->input('open_at');
        $closed_at = $request->input('closed_at');
        $published_at = $request->input('published_at');

        DB::beginTransaction();
        try {
            $update = Event::where('id', $id)->first();
            $update->title = $title;
            $update->description = $description;
            $update->featured_img = $featured_img;
            $update->event_type = $event_type;
            $update->location = $location;
            $update->htm = $htm;
            $update->option = $option;
            $update->author = $author;
            $update->status = $status;
            $update->open_at = $open_at;
            $update->closed_at = $closed_at;
            $update->published_at = $published_at;
            $update->update();

            $event_category = EventCategoryRelation::where('event_id', $id)->first();
            $event_category->event_id = $id;
            $event_category->category_id = json_encode($category);
            $event_category->update();     

            $event_forum = EventForumRelation::where('event_id', $id)->first();
            $event_forum->event_id = $id;
            $event_forum->forum_id = $forum_id;
            $event_forum->update();       

            $event_mentor = EventMentorRelation::where('event_id', $id)->first();
            $event_mentor->event_id = $id;
            $event_mentor->mentor_id = $mentor;
            $event_mentor->update();       

            DB::commit();
            return redirect($this->prefix.'edit-event/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect($this->prefix.'edit-event/'.$id)->with(['msg' => 'Error updating', 'status' => 'danger']);
        }

    }

    /**
     * Remove the specified event from storage.
     * @param $id
     * @return Response
     */
    public function destroy_event($id)
    {
        $this->EventHelper->delete_event($id);
    }

    /**
     * Remove multiple event from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_event(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->EventHelper->delete_event($id, 'bulk');
        }
        return redirect($this->prefix)->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of category.
     * @return Response
     */
    public function category(){
        $page_meta_title = 'Category';
        $category = EventCategory::get();
        return view('event::admin.category')->with(['page_meta_title' => $page_meta_title, 'category' => $category]);
    }

    /**
     * Get categories for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function get_category(Request $request){
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = EventCategory::orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('name', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();
        $output['recordsTotal'] = $query->count();
        $output['recordsFiltered'] = $output['recordsTotal'];
        $output['draw'] = intval($request->input('draw'));
        $output['length'] = 10;

        return $output;
    }

    /**
     * Show the form for creating a new category.
     * @return Response
     */
    public function create_category(){
        $page_meta_title = 'Category';
        $act = 'New';
        $action = $this->prefix.'store-category';
        $name = ''; 
        return view('event::admin.category_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'name' => $name]);
    }

    /**
     * Store a newly created category in storage.
     * @param  Request $request
     * @return Response
     */
    public function store_category(Request $request){
        $name = $request->input('name');
        $check = EventCategory::where('name', $name)->first();
        if (!isset($check)) {
            $slug = PostHelper::make_slug($name);
            $store = new EventCategory;
            $store->name = $name;
            $store->slug = $slug;
            if ($store->save()){
                return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
            } else {
                return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
            }
        } else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category already exist', 'status' => 'danger']);
        }
    }

    /**
     * Store a newly created category in storage. Ajax function.
     * @param  $name
     * @return Response
     */
    public function store_category_ajax($name){
        $check = EventCategory::where('name', $name)->first();
        if (!isset($check)) {
            $slug = PostHelper::make_slug($name);
            $store = new EventCategory;
            $store->name = $name;
            $store->slug = $slug;
            if ($store->save()){
                return  '<li><label><input selected name="category[]" type="checkbox" value="'.$store->id.'">'.$name.'</label></li>';
            } else {
                // do nothing
            }  
        } else {
            // do nothing
        }
    }

    /**
     * Show the form for editing the specified category.
     * @param $id
     * @return Response
     */
    public function edit_category($id){
        $page_meta_title = 'Category';
        $act = 'Edit';
        $action = $this->prefix.'update-category/'.$id;
        $category = EventCategory::where('id', $id)->first();

        if (isset($category)) {
            $name = $category->name;
            return view('event::admin.category_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'category' => $category, 'name' => $name]);
        }else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger']);
        }
    }

    /**
     * Update the specified category in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function update_category(Request $request, $id){
        $name = $request->input('name');
        $check = EventCategory::where('name', $name)->first();
        if (!isset($check)) {
            $update = EventCategory::where('id', $id)->first();
            $update->name = $name;
            if ($update->save()){
                return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
            } else {
                return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
            }
        } else {
            return redirect($this->prefix.'/edit-category/'.$id)->with(['msg' => 'Category name already used', 'status' => 'danger']);
        }
    }

    /**
     * Remove the specified category from storage.
     * @param $id
     * @return Response
     */
    public function destroy_category($id){
        $this->EventHelper->delete_category($id);
    }

    /**
     * Remove multiple category from storage.
     * @param Request $request
     * @return Response
     */
    public function bulk_delete_category(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $this->EventHelper->delete_category($id, 'bulk');
        }
        return redirect($this->prefix.'category')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }
}
