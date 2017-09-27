@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    @if(session('msg'))
    <div class="alert alert-{{ session('status') }}">
      {{ session('msg') }}
    </div>
    @endif
    <h3>{{ $event->title }}</h3>
    <small style="float: left;width: 100%;margin-bottom: 10px;"><a href="{{ url($prefix.'edit-event/'.$event->id) }}">Edit Event</a></small>
    <table>
        <tr>
            <th>featured img</th>
            <th>:</th>
            <th>{{ $event->featured_img }}</th>
        </tr>
        <tr>
            <th>description</th>
            <th>:</th>
            <th>{{ $event->description }}</th>
        </tr>
        <tr>
            <th>Forum</th>
            <th>:</th>
            <th>{{ $forum }}</th>
        </tr>
        <tr>
            <th>Mentor</th>
            <th>:</th>
            <th>{{ $mentor }}</th>
        </tr>
        <tr>
            <th>event_type</th>
            <th>:</th>
            <th>{{ $event->event_type }}</th>
        </tr>
        <tr>
            <th>location</th>
            <th>:</th>
            <th>{{ $event->htm }}</th>
        </tr>
        <tr>
            <th>meta title</th>
            <th>:</th>
            <th>{{ $meta_title }}</th>
        </tr>
        <tr>
            <th>htm</th>
            <th>:</th>
            <th>{{ $event->htm }}</th>
        </tr>
        <tr>
            <th>meta keyword</th>
            <th>:</th>
            <th>{{ $meta_keyword }}</th>
        </tr>
        <tr>
            <th>meta description</th>
            <th>:</th>
            <th>{{ $meta_desc }}</th>
        </tr>
        <tr>
            <th>author</th>
            <th>:</th>
            <th>{{ $event->author }}</th>
        </tr>
        <tr>
            <th>status</th>
            <th>:</th>
            <th>{{ $status }}</th>
        </tr>
        <tr>
            <th>open at</th>
            <th>:</th>
            <th>{{ $event->open_at }}</th>
        </tr>
        <tr>
            <th>closed at</th>
            <th>:</th>
            <th>{{ $event->closed_at }}</th>
        </tr>
        <tr>
            <th>published at</th>
            <th>:</th>
            <th>{{ $event->published_at }}</th>
        </tr>
    </table>

</div>
@stop