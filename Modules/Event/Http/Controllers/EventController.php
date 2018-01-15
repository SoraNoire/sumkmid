<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventCategoryRelation;
use Modules\Event\Entities\EventForumRelation;
use Modules\Event\Entities\EventMentorRelation;
use Modules\Blog\Entities\Categories;
use Modules\Blog\Entities\Tags;
use Modules\Blog\Entities\Media;
use Modules\Blog\Http\Helpers\PostHelper;
use Modules\Event\Http\Helpers\EventHelper;
use Carbon\Carbon;
use DB;
use View;

use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;

class EventController extends Controller
{
    public function __construct(){
        $this->EventHelper = new EventHelper;
        $this->prefix = 'admin/blog/event/';
        View::share('prefix', $this->prefix);
        View::share('body_id', 'event');
        View::share('tinymceApiKey', config('app.tinymce_api_key'));
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
        $event = Posts::where('slug', $slug)->first();
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
            return redirect(route('panel.event__index'))->with('msg', 'event Not Found')->with('status', 'danger');
        }
    }

    /**
     * Get events for datatable(ajax).
     * @param  Request $request
     * @return Response
     */
    public function ajaxEvents(Request $request)
    {
        $order = $request->order[0];
        $col = $request->columns["{$order['column']}"]['data'] ?? 'created_at'; 
        $direction = $order['dir'] ?? 'desc';
        
        $query = Posts::where('post_type','event')->where('deleted','0')->orderBy($col,$direction);
        $search = $request->search['value'];
        if (isset($search)) {
            $query = $query->where('title', 'like', '%'.$search.'%');   
        }
        $output['data'] = $query->get();

        $newdata = array();
        foreach ($output['data'] as $data) {
            $u= app()->OAuth->user($data->author);
            $name = $u->username ?? 'admin';
            if ($name != '') {
                $data->author_name = $name;
            }
            $newdata[] = $data;
        }
        $output['data'] = $newdata;
        
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
    public function addEvent()
    {
        $page_meta_title = 'Events';
        $u = app()->OAuth->mentors();
        $mentors = $u->users; 

        return view('event::admin.add_event')->with(['page_meta_title' => $page_meta_title, 'mentors' => $mentors]);
    }

    /**
     * Store a newly created event in storage.
     * @param  Request $request
     * @return Response
     */
    public function addEventPost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'open_date' => 'required',
            'closed_date' => 'required'
        ], PostHelper::validation_messages());

        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $description = $request->input('description');
        $event_type = $request->get('event_type');
        $location = $request->input('location');
        $gmaps_url = $request->input('gmaps_url');
        $event_url = $request->input('event_url'); 
        $author = app()->OAuth->Auth()->master_id;
        $mentor_registered = $request->get('mentor_registered');
        $mentor_not_registered = $request->get('mentor_not_registered');
        $status = $request->get('status');
        $meta_title = $request->input('meta_title') ?? '';
        $meta_desc = $request->input('meta_desc') ?? '';
        $meta_keyword = $request->input('meta_keyword') ?? '';
        $published_date = $request->input('published_date');
        $featured_image = $request->input('featured_image');

        $htm_free = $request->get('htm_free');
        if ($htm_free != 'free') {
            $htm = [];
            $htm_nominal = $request->input('htm_nominal');
            $htm_label = $request->input('htm_label');
            for ($i=0; $i < count($htm_nominal); $i++) { 
                 $htm[] = ['nominal' => $htm_nominal[$i], 'label' => $htm_label[$i]];
            } 
            $htm = json_encode($htm);
        } else {
            $htm = $htm_free;
        }
        $open_date = $request->input('open_date');
        $hour_open = $request->input('hour_open');
        $minute_open = $request->input('minute_open');
        $open_at = Carbon::parse($open_date.' '.$hour_open.':'.$minute_open);

        $closed_date = $request->input('closed_date');
        $hour_close = $request->input('hour_close');
        $minute_close = $request->input('minute_close');
        $closed_at = Carbon::parse($closed_date.' '.$hour_close.':'.$minute_close);
        
        $mentor_registered = json_encode($mentor_registered);
        $mentor_not_registered = json_encode($mentor_not_registered);

        if ($published_date == 'immediately') {
            $published_date = Carbon::now()->toDateTimeString();
        }

        $slug_check = Posts::where('slug', $slug)->first();
        if (isset($slug_check)) {
            $slug = $slug.'-'.date('s');
        }

        DB::beginTransaction();
        try {
            $store = new Posts;
            $store->title = $title;
            $store->slug = $slug;
            $store->author = $author;
            $store->content = $description;
            $store->post_type = 'event';
            $store->status = $status;
            $store->published_date = $published_date;
            $store->featured_image = $featured_image;
            $store->save();

            $meta_contents = array();
            $metas[] = ['name' => 'event_type', 'value' => $event_type];
            $metas[] = ['name' => 'location', 'value' => $location];
            $metas[] = ['name' => 'htm', 'value' => $htm];
            $metas[] = ['name' => 'open_at', 'value' => $open_at];
            $metas[] = ['name' => 'closed_at', 'value' => $closed_at];
            $metas[] = ['name' => 'mentor_registered', 'value' => $mentor_registered];
            $metas[] = ['name' => 'mentor_not_registered', 'value' => $mentor_not_registered];
            $metas[] = ['name' => 'event_url', 'value' => $event_url];
            $metas[] = ['name' => 'meta_title', 'value' => $meta_title];
            $metas[] = ['name' => 'meta_desc', 'value' => $meta_desc];
            $metas[] = ['name' => 'meta_keyword', 'value' => $meta_keyword];
            $metas[] = ['name' => 'gmaps_url', 'value' => $gmaps_url];
            foreach ($metas as $meta) {
                if ($meta['value'] != '') {
                    $meta_contents[] = [ 'post_id'=>$store->id, 'key'=> $meta['name'], 'value'=> $meta['value'] ];
                }
            }

            PostMeta::insert($meta_contents);

            DB::commit();
            return redirect(route('panel.event__view', $store->id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.event__index'))->with(['msg' => 'Error Saving '.substr($e, 0, 50), 'status' => 'danger']);
        }

        
    }

    /**
     * Show the form for editing event.
     * @param $id
     * @return Response
     */
    public function viewEvent($id)
    {
        $page_meta_title = 'Events';
        $act = 'Edit';
        $action = $this->prefix.'update-event/'.$id;
        $event = Posts::where('id', $id)->first();
        if (isset($event)) {

            $post_metas = PostMeta::where('post_id',$event->id)->get();

            $title = $event->title;
            $description = $event->content; 
            $media = Media::orderBy('created_at','desc')->get();
            $status = $event->status;
            $published_date = $event->published_date;
            $featured_image = $event->featured_image;
            
            $post_metas = $this->readMetas($post_metas);

            $event_type     = $post_metas->event_type ?? '';
            $location       = $post_metas->location ?? '';
            $htm            = $post_metas->htm ?? '';
            $open_at        = $post_metas->open_at ?? '';
            $closed_at      = $post_metas->closed_at ?? '';
            $event_url      = $post_metas->event_url ?? '';
            $meta_desc      = $post_metas->meta_desc ?? '';
            $meta_title     = $post_metas->meta_title ?? '';
            $meta_keyword   = $post_metas->meta_keyword ?? '';
            $mentor_registered      = json_decode($post_metas->mentor_registered ?? '') ?? [];
            $mentor_not_registered      = json_decode($post_metas->mentor_not_registered ?? '') ?? [];
            $gmaps_url      = $post_metas->gmaps_url ?? '';

            if (is_string($htm) && $htm != 'free') {
                $htm = json_decode($htm);
            }

            $open_at = Carbon::parse($open_at);
            $open_date = $open_at->toDateString();
            $hour_open = $open_at->hour;
            if ($hour_open < 10) {
                $hour_open = '0'.$hour_open;
            }
            $minute_open = $open_at->minute;
            if ($minute_open < 10) {
                $minute_open = '0'.$minute_open;
            }

            $closed_at = Carbon::parse($closed_at);
            $closed_date = $closed_at->toDateString();
            $hour_close = $closed_at->hour;
            if ($hour_close < 10) {
                $hour_close = '0'.$hour_close;
            }
            $minute_close = $closed_at->minute;
            if ($minute_close < 10) {
                $minute_close = '0'.$minute_close;
            }

            $mentors = app()->OAuth->mentors()->users;

            return view('event::admin.edit_event')->with(
                            [
                                'gmaps_url' => $gmaps_url,
                                'item_id' => $id,
                                'id'=>$id,
                                'page_meta_title' => $page_meta_title,
                                'act' => $act,
                                'action' => $action,
                                'event' => $event ,
                                'title' => $title,
                                'description' => $description,
                                'media' => $media,
                                'meta_desc' => $meta_desc,
                                'meta_title' => $meta_title,
                                'meta_keyword' => $meta_keyword,
                                'status' => $status,
                                'published_date' => $published_date,
                                'event_type' => $event_type,
                                'mentors' => $mentors,
                                'mentor_registered' => $mentor_registered,
                                'mentor_not_registered' => $mentor_not_registered,
                                'location' => $location,
                                'htm' => $htm,
                                'open_date' => $open_date,
                                'hour_open' => $hour_open,
                                'minute_open' => $minute_open,
                                'closed_date' => $closed_date,
                                'hour_close' => $hour_close,
                                'minute_close' => $minute_close,
                                'event_url' => $event_url,
                                'featured_image' => $featured_image,
                            ]
                    );
        } else {
            return redirect(route('panel.event__index'))->with(['msg' => 'Event Not Found', 'status' => 'danger']);
        }
    }

    function readMetas($arr=[])
    {
        $metas = new \stdClass;;
        foreach ($arr as $key => $value) {
            $metas->{$value->key} = $value->value;
        }
        return $metas;
    }

    /**
     * Update the specified event in storage.
     * @param  Request $request, $id
     * @return Response
     */
    public function updateEvent(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'open_date' => 'required',
            'closed_date' => 'required'
        ], PostHelper::validation_messages());
        
        $title = $request->input('title');
        $description = $request->input('description');
        $event_type = $request->get('event_type');
        $location = $request->input('location');
        $gmaps_url = $request->input('gmaps_url');
        $mentor_registered = $request->get('mentor_registered');
        $mentor_not_registered = $request->get('mentor_not_registered');
        $status = $request->get('status');
        $meta_title = $request->input('meta_title');
        $meta_desc = $request->input('meta_desc');
        $meta_keyword = $request->input('meta_keyword');
        $published_date = $request->input('published_date');
        $event_url = $request->input('event_url'); 
        $featured_image = $request->input('featured_image'); 

        $open_date = $request->input('open_date');
        $hour_open = $request->input('hour_open');
        $minute_open = $request->input('minute_open');
        $open_at = Carbon::parse($open_date.' '.$hour_open.':'.$minute_open);

        $closed_date = $request->input('closed_date');
        $hour_close = $request->input('hour_close');
        $minute_close = $request->input('minute_close');
        $closed_at = Carbon::parse($closed_date.' '.$hour_close.':'.$minute_close);

        $htm_free = $request->get('htm_free');
        if ($htm_free != 'free') {
            $htm = [];
            $htm_nominal = $request->input('htm_nominal');
            $htm_label = $request->input('htm_label');
            for ($i=0; $i < count($htm_nominal); $i++) { 
                 $htm[] = ['nominal' => $htm_nominal[$i], 'label' => $htm_label[$i]];
            } 
            $htm = json_encode($htm);
        } else {
            $htm = $htm_free;
        }

        $mentor_registered = json_encode($mentor_registered);
        $mentor_not_registered = json_encode($mentor_not_registered);

        DB::beginTransaction();
        try {
            $post_metas = PostMeta::where('post_id',$id)->get();
            $update = Posts::where('id', $id)->first();
            $update->title = $title;
            $update->content = $description;
            $update->status = $status;
            $update->published_date = $published_date;
            $update->featured_image = $featured_image;
            
            if($update->update())
            {
                $newMeta = false;
                $post_metas = PostMeta::where('post_id',$id)->get();
                $meta_fields = ['event_type', 'location', 'meta_title', 'meta_desc', 'meta_keyword', 'event_url', 'gmaps_url' ];

                foreach ($meta_fields as $key => $meta) {
                    $updated = false;
                    $post_metas->map(function($field) use ($meta,$request,&$updated){
                        if ( $meta == $field->key )
                        {
                            $value = ( 
                                        is_array($request->input($field->key)) ||
                                        is_object($request->input($field->key)) 
                                    )
                                    ? json_encode($request->input($field->key)) : $request->input($field->key);
                            $field->value = $value ?? '';
                            $field->save();
                            $updated = true;
                            return true;
                        }
                    });
                    if(!$updated && $request->input($meta))
                    {
                        $value = ( 
                                    is_array($request->input($meta)) ||
                                    is_object($request->input($meta)) 
                                )
                                ? json_encode($request->input($meta)) : $request->input($meta);
                         PostMeta::insert(['post_id'=>$update->id,'key' => $meta, 'value'=>$value]);
                    }
                }

                $other_meta = array();
                $other_meta[] = ['name' => 'open_at', 'value' => $open_at];
                $other_meta[] = ['name' => 'closed_at', 'value' => $closed_at];
                $other_meta[] = ['name' => 'htm', 'value' => $htm];
                $other_meta[] = ['name' => 'mentor_registered', 'value' => $mentor_registered];
                $other_meta[] = ['name' => 'mentor_not_registered', 'value' => $mentor_not_registered];
            
                foreach ($other_meta as $other_meta) {
                    $post_meta = PostMeta::where('post_id',$id)->where('key', $other_meta['name'])->first();
                    if (isset($post_meta)) {
                        $post_meta->value = $other_meta['value'];
                        $post_meta->save();
                    } else {
                        PostMeta::insert(['post_id' => $update->id, 'key' => $other_meta['name'], 'value' => $other_meta['value']]);
                    }
                }
            }

            // $meta_fields = [ 'event_type', 'event_location', 'event_htm', 'event_meta_title', 'event_meta_desc', 'event_meta_keyword', 'event_open_at', 'event_closed_at', 'event_categories' ];
            // foreach ($post_metas as $key => &$value) {
            //     if( in_array($value->key, $meta_fields)){
            //         $fieldCheck = ( 
            //                         'event_type' == $value->key
            //                         ) ? $value->key : str_ireplace("event_", "", $value->key); 

            //         if ( $request->input($fieldCheck))
            //         {
            //             $value->value = ( is_array($request->input($fieldCheck)) || is_object($request->input($fieldCheck)) ) ? json_encode($request->input($fieldCheck)) : $request->input($fieldCheck);
            //             $value->save();
            //         }
            //     }
            // }

            DB::commit();
            return redirect(route('panel.event__view', $id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('panel.event__view', $id))->with(['msg' => 'Error updating', 'status' => 'danger']);
        }

    }

    /**
     * Remove the specified event from storage.
     * @param $id
     * @return Response
     */
    public function removeEvent($id)
    {
        $this->EventHelper->delete_event($id);
    }

    /**
     * Remove multiple event from storage.
     * @param Request $request
     * @return Response
     */
    public function massdeleteEvent(Request $request)
    {
        $id = json_decode($request->id);
        foreach ($id as $id) {
            $delete = Posts::find($id);
            if ($delete) {
                $delete->deleted = 1;
                if (!$delete->save()) {
                    return redirect(route('panel.event__index'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('panel.event__index'))->with(['msg' => 'Delete Error. Event does not exists', 'status' => 'danger']);
            }
        }
        return redirect(route('panel.event__index'))->with(['msg' => 'Delete Success', 'status' => 'success']);
    }

    /**
     * Display a listing of category.
     * @return Response
     */
    public function category(){
        $page_meta_title = 'Category';
        $category = categories::get();
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
