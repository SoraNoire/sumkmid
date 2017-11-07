@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    @if(session('msg'))
    <div class="alert alert-{{ session('status') }}">
      {{ session('msg') }}
    </div>
    @endif
    <h3>{{ $gallery->title }}</h3>
    <small style="float: left;width: 100%;margin-bottom: 10px;"><a href="{{ url($prefix.'edit-gallery/'.$gallery->id) }}">Edit gallery</a></small>
    <table>
        <tr>
            <th></th>
        </tr>
        <tr>
            <td>Gallery</td>
            <td>:</td>
            <td>{{ $gallery->body }}</td>
        </tr>
        <tr>
            <td>Featured Image</td>
            <td>:</td>
            <td>{{ $gallery->featured_img }}</td>
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
            <td>Gallery Url</td>
            <td>:</td>
            <td>{{ $gallery->gallery_url }}</td>
        </tr>
    </table>

</div>
@stop