@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    @if(session('msg'))
    <div class="alert alert-{{ session('status') }}">
      {{ session('msg') }}
    </div>
    @endif
    <h3>{{ $post->title }}</h3>
    <small style="float: left;width: 100%;margin-bottom: 10px;"><a href="{{ url($prefix.'edit-post/'.$post->id) }}">Edit Post</a></small>
    <table>
        <tr>
            <th></th>
        </tr>
        <tr>
            <td>Post</td>
            <td>:</td>
            <td>{{ $post->body }}</td>
        </tr>
        <tr>
            <td>Featured Image</td>
            <td>:</td>
            <td>{{ $post->featured_img }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>:</td>
            <td>@foreach ($category as $category) {{ $category->name }}, @endforeach</td>
        </tr>
        <tr>
            <td>Tag</td>
            <td>:</td>
            <td>@foreach ($tag as $tag) {{ $tag->name }}, @endforeach</td>
        </tr>
    </table>

</div>
@stop