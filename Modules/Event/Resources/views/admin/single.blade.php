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
            <th></th>
        </tr>
    </table>

</div>
@stop