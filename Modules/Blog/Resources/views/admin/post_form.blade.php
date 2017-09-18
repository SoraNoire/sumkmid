@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
    <h4 class="title">{{ $act }} Posts</h4>

    <form id="post-form" method="post" action="{{ url($action) }}" accept-charset="UTF-8">
        @if ($act == 'New')
        <a href="{{ URL::to($prefix.'create-post') }}" class="btn btn-round btn-fill btn-info">New Post +<div class="ripple-container"></div></a>
        @elseif ($act == 'Edit')
        <a target="_blank" href="{{ URL::to('/blog/show/'.$post->slug) }}" class="btn btn-round btn-fill btn-info">View Post<div class="ripple-container"></div></a>
        <a onclick="return confirm('Delete Post?');" href="{{URL::to('/blog/delete-post/'.$post->id)}}" class="btn btn-round btn-fill btn-danger">Delete Post<div class="ripple-container"></div></a>
        @endif
        <button type="submit" class="btn btn-success pull-right">Save Post</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-9">
                <div class="form-group">
                    <input class="form-control" type="text" name="title" value="{{ $title }}" placeholder="Enter Title Here">
                </div>
                <div class="form-group">
                    <label class="control-label">Post Content</label>
                    <textarea class="form-control mytextarea" name="body">{{ $body }}</textarea>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          SEO Setting <a data-toggle="collapse" href="#post-seo"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-seo" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Meta Title</label>
                                <input value="{{ $meta_title }}" class="form-control" type="text" name="meta_title" maxlength="191">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Meta Deskripsi</label>
                                <textarea class="form-control" id="inputan" name="meta_desc" style="min-height: 100px;" maxlength="300">{{ $meta_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Keyword</label>
                                <input value="{{ $meta_keyword }}" class="form-control" type="text" name="meta_keyword" maxlength="191">
                                <small>Contoh : keyword 1, keyword 2, keyword 3</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Publish <a data-toggle="collapse" href="#post-"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Post Status</label>
                                <select name="status" class="form-control">
                                    <option {{ $status == 1 ? 'selected' : '' }} value="1">Published</option>
                                    <option {{ $status == 0 ? 'selected' : '' }} value="0">Draft</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Date Published</label>
                                <div class="input-group input-append date post-datetime">
                                    <input class="form-control" size="16" type="text" value="{{ $published_at }}" name="published_at" readonly>
                                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Post Type <a data-toggle="collapse" href="#post-"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <select id="post-type" name="post_type" class="form-control" onchange="select_post_type()">
                                <option value="standard" {{ $post_type == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="video" {{ $post_type == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="gallery" {{ $post_type == 'gallery' ? 'selected' : '' }}>Gallery</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Category <a data-toggle="collapse" href="#post-category"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-category" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <label class="control-label">All Category</label>
                            <div class="category-wrap">
                                <ul>

                                </ul>
                            </div>
                            <a data-toggle="collapse" data-target="#add_category" href="#add_category"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</a>

                            <div id="add_category" class="collapse">
                                <div class="form-group">
                                    <label class="control-label">Add Category</label>
                                    <input type="text" name="category_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <select id="CategoryParent" name="category_parent" class="form-control" style="width: 100%;"></select>
                                </div>
                                <button class="btn btn-default" type="button" id="add_category_button">Add New Category</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Tag <a data-toggle="collapse" href="#post-tag"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-tag" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <select id="mytag" name="tag[]" class="mytag form-control" multiple>
                                @foreach ($alltag as $tag)
                                    <option {{ is_array($selected_tag) && in_array($tag->id, $selected_tag) ? 'selected' : ''}} value="{{$tag->name}}" >{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Featured Image <a data-toggle="collapse" href="#post-fimg"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-fimg" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <a id="browse_media_post" data-toggle="modal" data-target="#myModal" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Set Featured Image</a>
                            <input type="hidden" name="featured_img" id="featured_img" value="{{ $featured_img }}">
                            <div class="preview-fimg-wrap" style="display: {{ $featured_img != '' ? 'block' : ''  }};">
                                <div class="preview-fimg" style="background-image: url({{ $featured_img }});"></div>
                                <a href="#" onclick="remove_fimg()" class="remove-fimg"><i class="fa fa-times" aria-hidden="true"></i> Remove Featured Image</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </form>

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
