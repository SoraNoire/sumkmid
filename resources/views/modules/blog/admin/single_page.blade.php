@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    <h3>{{ $page->title }}</h3>
    <small style="float: left;width: 100%;margin-bottom: 10px;"><a href="{{ url($prefix.'edit-page/'.$page->id) }}">Edit Page</a></small>
    <table>
        <tr>
            <th></th>
        </tr>
        <tr>
            <td>Page</td>
            <td>:</td>
            <td>{{ $page->body }}</td>
        </tr>
        <tr>
            <td>Featured Image</td>
            <td>:</td>
            <td>{{ $page->featured_img }}</td>
        </tr>
    </table>
</div>
@stop
