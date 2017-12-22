@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
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
            <td>Files</td>
            <td>:</td>
            <td>
                @if($files != '')
                    @foreach($files as $file)
                        <a href="{{ PostHelper::getLinkFile($file->file_doc, 'files') }}">{{ $file->file_label }}</a>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>

</div>
@stop