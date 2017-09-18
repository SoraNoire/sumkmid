@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
@if ($act == 'New')
<td class="text-center">
<a href="{{ URL::to('blog/create-page') }}" class="btn btn-round btn-fill btn-info">New Page +<div class="ripple-container"></div></a>
</td>
@elseif ($act == 'Edit')
<td class="text-center">
<a target="_blank" href="{{ URL::to('/blog/page/'.$page->slug) }}" class="btn btn-round btn-fill btn-info">View Page<div class="ripple-container"></div></a>
</td>
<td><a onclick="return confirm('Delete Page?');" href="{{URL::to('/blog/delete-page/'.$page->id)}}" class="btn btn-round btn-fill btn-danger">Delete Page<div class="ripple-container"></div></a></td>
@endif
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Pages</h4>
        <p class="category">{{ $act }} Page</p>
    </div>

    <form method="post" action="{{ url($action) }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label">Title</label>
            <input class="form-control" type="text" name="title" value="{{ $title }}">
        </div>
        <div class="form-group">
            <label class="control-label">Page Content</label>
            <textarea class="form-control mytextarea" name="body">{{ $body }}</textarea>
        </div>
        <div class="form-group">
            <label>Featured Image</label>
            <div class="row">
                <div class="col-md-2">
                    <a id="browse_media_post" data-toggle="modal" data-target="#myModal" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Browse Media</a>
                </div>
                <div class="col-md-10">
                    <input id="fimg" class="form-control" type="text" name="fimg" disabled value="{{ $featured_img }}">
                    <input type="hidden" name="featured_img" id="featured_img" value="{{ $featured_img }}">
                </div>
            </div>
        </div>  
        <button type="submit" class="btn btn-success pull-right">Save</button>
    </form>
</div>
</div>
@stop

@section('modal')
@if(isset($media))
<div class="overlay"></div>
<div class="custom-modal">
<div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;">
<div class="form-group" style="margin-top: 0px;margin-bottom: 0px;padding-bottom: 0px;cursor: default;">
    <form id="actuploadmedia" method="post" action="{{ URL::to('/blog/store-media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;" multiple>
    </form>
</div>
</div>
<div style="float: right;" id="close_media_post" data-toggle="modal" data-target="#myModal" class="btn btn-round btn-fill btn-default">Close</div>
    <div class="card">
        <div class="card-header" data-background-color="blue">
            <h4 class="title">Browse Media</h4>
            <p class="category">Cari Media untuk ditambahkan</p>
        </div>
    <div class="card-content table-responsive">
        <table style="width: 100%;" class="table mediatable" id="FeaturedImg">
            <thead >
                <th>Preview</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </thead>
        </table>
    </div>
    </div>
</div>
@endif
@endsection