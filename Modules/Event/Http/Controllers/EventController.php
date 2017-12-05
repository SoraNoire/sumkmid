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

use Modules\Blog\Entities\Posts;
use Modules\Blog\Entities\PostMeta;

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
            return redirect($this->prefix.'events')->with('msg', 'event Not Found')->with('status', 'danger');
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
        $media = Media::orderBy('created_at','desc')->get();
        $published_date = 'immediately';
        $list_category = EventHelper::get_list_category();

        return view('event::admin.add_event')->with(['page_meta_title' => $page_meta_title, 'media' => $media, 'published_date' => $published_date, 'list_category' => $list_category]);
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
            'description' => 'required'
        ]);

        $title = $request->input('title');
        $slug = PostHelper::make_slug($title);
        $description = $request->input('description');
        $featured_image = $request->input('featured_image');
        $event_type = $request->get('event_type');
        $categories = $request->get('categories');
        $location = $request->input('location');
        $htm = $request->input('htm');
        $event_url = $request->input('event_url'); 
        $author = app()->SSO->Auth()->id;
        $mentor = $request->get('mentor');
        $status = $request->get('status');
        $meta_title = $request->input('meta_title') ?? '';
        $meta_desc = $request->input('meta_desc') ?? '';
        $meta_keyword = $request->input('meta_keyword') ?? '';
        $open_at = $request->input('open_at');
        $closed_at = $request->input('closed_at');
        $published_date = $request->input('published_date');

        $categories = json_encode($categories);
        $mentor = json_encode($mentor);

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
            $store->featured_image = $featured_image;
            $store->author = $author;
            $store->content = $description;
            $store->post_type = 'event';
            $store->status = $status;
            $store->published_date = $published_date;
            $store->save();

            $meta_contents = array();
            $metas[0] = ['name' => 'event_type', 'value' => $event_type];
            $metas[1] = ['name' => 'event_location', 'value' => $location];
            $metas[2] = ['name' => 'event_htm', 'value' => $htm];
            $metas[3] = ['name' => 'event_meta_title', 'value' => $meta_title];
            $metas[4] = ['name' => 'event_meta_desc', 'value' => $meta_desc];
            $metas[5] = ['name' => 'event_meta_keyword', 'value' => $meta_keyword];
            $metas[6] = ['name' => 'event_open_at', 'value' => $open_at];
            $metas[7] = ['name' => 'event_closed_at', 'value' => $closed_at];
            $metas[8] = ['name' => 'event_categories', 'value' => $categories];
            $metas[9] = ['name' => 'event_mentor', 'value' => $mentor];
            $metas[10] = ['name' => 'event_url', 'value' => $event_url];
            foreach ($metas as $meta) {
                if ($meta['value'] != '') {
                    $meta_contents[] = [ 'post_id'=>$store->id, 'key'=> $meta['name'], 'value'=> $meta['value'] ];
                }
            }

            PostMeta::insert($meta_contents);

            DB::commit();
            return redirect(route('events'))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('events'))->with(['msg' => 'Error Saving '.substr($e, 0, 50), 'status' => 'danger']);
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
            $featured_image = $event->featured_image;
            $media = Media::orderBy('created_at','desc')->get();
            $status = $event->status;
            $published_date = $event->published_date;
            
            $post_metas = $this->readMetas($post_metas);

            $event_type     = $post_metas->event_type ?? '';
            $location       = $post_metas->event_location ?? '';
            $htm            = $post_metas->event_htm ?? '';
            $open_at        = $post_metas->event_open_at ?? '';
            $closed_at      = $post_metas->event_closed_at ?? '';
            $event_url      = $post_metas->event_url ?? '';
            $meta_desc      = $post_metas->event_meta_desc ?? '';
            $meta_title     = $post_metas->event_meta_title ?? '';
            $meta_keyword   = $post_metas->event_meta_keyword ?? '';
            $mentor_id      = json_decode($post_metas->event_mentor ?? '') ?? [];
            $categories     = json_decode($post_metas->event_categories ?? '') ?? [];
            
            $list_category = EventHelper::get_list_category($categories);

            return view('event::admin.edit_event')->with(
                            [
                                'id'=>$id,
                                'page_meta_title' => $page_meta_title,
                                'act' => $act,
                                'action' => $action,
                                'event' => $event ,
                                'title' => $title,
                                'description' => $description,
                                'media' => $media,
                                'featured_image' => $featured_image,
                                'meta_desc' => $meta_desc,
                                'meta_title' => $meta_title,
                                'meta_keyword' => $meta_keyword,
                                'status' => $status,
                                'published_date' => $published_date,
                                'event_type' => $event_type,
                                'mentor_id' => $mentor_id,
                                'location' => $location,
                                'htm' => $htm,
                                'open_at' => $open_at,
                                'closed_at' => $closed_at,
                                'list_category' => $list_category,
                                'event_url' => $event_url
                            ]
                    );
        } else {
            return redirect($this->prefix)->with(['msg' => 'Event Not Found', 'status' => 'danger']);
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
            'description' => 'required'
        ]);
        
        $title = $request->input('title');
        $description = $request->input('description');
        $featured_image = $request->input('featured_image');
        // $event_type = $request->get('event_type');
        // $categories = json_encode($request->get('category'));
        $location = $request->input('location');
        // $htm = $request->input('htm');
        // $forum_id = $request->input('forum_id');
        $mentor = $request->input('mentor');
        $status = $request->get('status');
        // $event_type = $request->get('event_type');
        // $event_meta_title = $request->input('meta_title');
        // $event_meta_desc = $request->input('meta_desc');
        // $event_meta_keyword = $request->input('meta_keyword');
        // $open_at = $request->input('open_at');
        // $closed_at = $request->input('closed_at');
        $published_date = $request->input('published_date');

        DB::beginTransaction();
        try {

            $post_metas = PostMeta::where('post_id',$id)->get();
            $update = Posts::where('id', $id)->first();
            $update->title = $title;
            $update->content = $description;
            $update->featured_image = $featured_image;
            $update->status = $status;
            $update->published_date = $published_date;
            
            if($update->update())
            {
                $newMeta = false;
                $post_metas = PostMeta::where('post_id',$id)->get();
                $meta_fields = ['event_type', 'location', 'htm', 'open_at', 'closed_at', 'categories', 'forum_id', 'meta_title', 'meta_desc', 'meta_keyword', 'mentor' ];

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
                            $field->value = $value ?? $field->value;
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
            return redirect(route('viewevent',$id))->with(['msg' => 'Saved', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('viewevent',$id))->with(['msg' => 'Error updating', 'status' => 'danger']);
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
                    return redirect(route('pages'))->with(['msg' => 'Delete Error', 'status' => 'danger']);
                }
            } else {
                return redirect(route('pages'))->with(['msg' => 'Delete Error. Event does not exists', 'status' => 'danger']);
            }
        }
        return redirect(route('pages'))->with(['msg' => 'Delete Success', 'status' => 'success']);
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
