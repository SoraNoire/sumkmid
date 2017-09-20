@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    @if(session('msg'))
    <div class="alert alert-{{ session('status') }}">
      {{ session('msg') }}
    </div>
    @endif
    <h3>{{ $video->title }}</h3>
    <small style="float: left;width: 100%;margin-bottom: 10px;"><a href="{{ url($prefix.'edit-video/'.$video->id) }}">Edit video</a></small>
    <table>
        <tr>
            <th></th>
        </tr>
        <tr>
            <td>Video</td>
            <td>:</td>
            <td>{{ $video->body }}</td>
        </tr>
        <tr>
            <td>Featured Image</td>
            <td>:</td>
            <td>{{ $video->featured_img }}</td>
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
        <tr>
            <td>Meta Title</td>
            <td>:</td>
            <td>{{ $meta_title }}</td>
        </tr>
        <tr>
            <td>Meta Desc</td>
            <td>:</td>
            <td>{{ $meta_desc }}</td>
        </tr>
        <tr>
            <td>Meta Keyword</td>
            <td>:</td>
            <td>{{ $meta_keyword }}</td>
        </tr>
        <tr>
            <td>Video Url</td>
            <td>:</td>
            <td>{{ $video->video_url }}</td>
        </tr>
    </table>

</div>
@stop