<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventCategoryRelation;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Event\Http\Helpers\EventHelper;
use Carbon\Carbon;
use DB;
use View;

class EventController extends Controller
{
    public function __construct(){
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

            return view('event::admin.single')->with(['page_meta_title' => $page_meta_title, 'event' => $event, 'category' => $category]);
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
        $allcategory = EventHelper::get_list_category();
        $meta_desc = '';
        $meta_title = '';
        $meta_keyword = '';
        $status = 1;
        $published_at = 'immediately';
        $event_type = 'online';
        $forum_id = '';
        $mentor_id = '';
        $location = '';
        $htm = '';
        $open_at = '';
        $closed_at = '';

        return view('event::admin.event_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'title' => $title, 'description' => $description, 'allcategory' => $allcategory, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at, 'event_type' => $event_type, 'forum_id' => $forum_id]);
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
        $category = $request->get('category');
        $featured_img = $request->input('featured_img');
        $event_type = $request->get('event_type');
        $forum_id = 1;
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        if ($published_at = 'immediately') {
            $published_at = Carbon::now()->toDateTimeString();
        }

        $slug_check = Event::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::transaction(function() use ($title, $slug, $category, $description, $featured_img, $option, $status, $published_at, $event_type, $forum_id) {
            $store = new Event;
            $store->title = $title;
            $store->slug = $slug;
            $store->description = $description;
            $store->featured_img = $featured_img;
            $store->event_type = $event_type;
            $store->featured_img = $featured_img;
            $store->author = 1;
            $store->status = $status;
            $store->option = $option;
            $store->published_at = $published_at;
            $store->save();

            $event_category = new EventCategoryRelation;
            $event_category->event_id = $store->id;
            $event_category->category_id = json_encode($category);
            $event_category->save();
        });
        return redirect($this->prefix)->with(['msg' => 'Saved', 'status' => 'success']);
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
            $allcategory = EventHelper::get_list_category($event->id);
            $featured_img = $event->featured_img;
            $media = Media::orderBy('created_at','desc')->get();           
            $option = json_decode($event->option);
            $meta_desc = $option->meta_desc;
            $meta_title = $option->meta_title;
            $meta_keyword = $option->meta_keyword;
            $status = $event->status;
            $published_at = $event->published_at;

            return view('event::admin.event_form')->with(['page_meta_title' => $page_meta_title, 'act' => $act, 'action' => $action, 'event' => $event , 'title' => $title, 'description' => $description, 'allcategory' => $allcategory, 'media' => $media, 'featured_img' => $featured_img, 'meta_desc' => $meta_desc, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'status' => $status, 'published_at' => $published_at]);
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
        $category = $request->get('category');
        $featured_img = $request->input('featured_img');
        $status = $request->get('status');
        $published_at = $request->input('published_at');
        $option['meta_title'] = $request->input('meta_title');
        $option['meta_desc'] = $request->input('meta_desc');
        $option['meta_keyword'] = $request->input('meta_keyword');
        $option = json_encode($option);

        DB::transaction(function() use ($id, $title, $category, $description, $featured_img, $option, $status, $published_at) {
            $update = Event::where('id', $id)->first();
            $update->title = $title;
            $update->description = $description;
            $update->featured_img = $featured_img;
            $update->event_type = $event_type;
            $update->featured_img = $featured_img;
            $update->author = 1;
            $update->status = $status;
            $update->option = $option;
            $update->published_at = $published_at;
            $update->update();

            $event_category = EventCategoryRelation::where('event_id', $id)->first();
            $event_category->event_id = $update->id;
            $event_category->category_id = json_encode($category);
            $event_category->update();
        });
        return redirect($this->prefix.'edit-event/'.$id)->with(['msg' => 'Saved', 'status' => 'success']);

    }

    /**
     * Remove the specified event from storage.
     * @param $id
     * @return Response
     */
    public function destroy_event($id)
    {
        $event = Event::where('id', $id)->first();
        if (isset($event)) {
            if ($event->delete()) {
                return redirect($this->prefix)->with(['msg' => 'Deleted', 'status' => 'success']);
            } else {
                return redirect($this->prefix)->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        } else {
            return redirect($this->prefix)->with(['msg' => 'event Not Found', 'status' => 'danger']);
        }
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
            $event = Event::where('id', $id)->first();
            if (isset($event)) {
                if ($event->delete()) {
                    // do nothing
                } else {
                    return redirect($this->prefix)->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect($this->prefix)->with(['msg' => 'Delete Error. event Not Found', 'status' => 'danger']);
            }
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
        $slug = PostHelper::make_slug($request->input('name'));
        $store = new EventCategory;
        $store->name = $request->input('name');
        $store->slug = $slug;
        if ($store->save()){
            return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
    }

    /**
     * Store a newly created category in storage. Ajax function.
     * @param  $name, $parent
     * @return Response
     */
    public function store_category_ajax($name, $parent){
        $slug = EventHelper::make_slug($name);
        $store = new EventCategory;
        $store->name = $name;
        $store->slug = $slug;
        if ($store->save()){
            return 'success saving category';
        } else {
            return 'error saving category';
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
            $maincategory = EventCategory::where('parent', null)->get();
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
        $update = EventCategory::where('id', $id)->first();
        $update->name = $request->input('name');
        if ($update->save()){
            return redirect($this->prefix.'category')->with(['msg' => 'Saved', 'status' => 'success']);
        } else {
            return redirect($this->prefix.'category')->with(['msg' => 'Save Error', 'status' => 'danger']);
        }
        return redirect($this->prefix.'category');
    }

    /**
     * Remove the specified category from storage.
     * @param $id
     * @return Response
     */
    public function destroy_category($id){
        $category = EventCategory::where('id', $id)->first();
        if (isset($category)) {
            if ($category->delete()) {
                return redirect($this->prefix.'category')->with(['msg' => 'Deleted', 'status' => 'success']);
            } else {
                return redirect($this->prefix.'category')->with(['msg' => 'Delete Error', 'status' => 'danger']);
            }
        }else {
            return redirect($this->prefix.'category')->with(['msg' => 'Category Not Found', 'status' => 'danger']);
        }
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
            $category = EventCategory::where('id', $id)->first();
            if (isset($category)) {
                if ($category->delete()) {
                    // do nothing
                } else {
                    return redirect($this->prefix.'category')->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect($this->prefix.'category')->with(['msg' => 'Delete Error. Category Not Found', 'status' => 'danger']);
            }
        }
        return redirect($this->prefix.'category')->with(['msg' => 'Delete Success', 'status' => 'success']);
    }
}
