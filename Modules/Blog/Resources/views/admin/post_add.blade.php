@extends('blog::layouts.master')

@section('content')
<script> postId = {{$post->id ?? 0}}</script>
<div class="col-md-12">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable ">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        There is some error. Please check again
    </div>
    @endif
    <h4 class="title">New Post</h4>

    <form id="post-form" method="post" action="{{ route('panel.post__save') }}" accept-charset="UTF-8">
        @if (in_array('write', app()->OAuth::can('panel.post')))
        <a href="{{ route('panel.post__add') }}" class="btn btn-round btn-fill btn-info">
            New Post +<div class="ripple-container"></div>
        </a>
        @endif

        <button type="submit" class="btn btn-success pull-right">Save Post</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="control-label">Title</label>
                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="Enter Title Here" required="required">
                </div>

                @if (in_array('read', app()->OAuth::can('panel.media')))
                <a id="browse_media_post" data-toggle="modal" data-target="#myMedia" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Add Media</a>
                @endif

                <div class="form-group">
                    <label class="control-label">Post Content</label>
                    @if ($errors->has('content'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>This field is required</strong>
                        </span>
                    </div>
                    @endif
                    <textarea class="form-control mytextarea" name="content">{{ old('content') }}</textarea>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Add File <a data-toggle="collapse" href="#post-file"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-file" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <a id="browse_file_post" data-toggle="modal" data-target="#myFile" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Browse File</a>

                            <div class="panel-body">
                                <div class="file-list">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <input value="{{ old('meta_title') }}" class="form-control" type="text" name="meta_title" maxlength="191">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Meta Deskripsi</label>
                                <textarea class="form-control" id="inputan" name="meta_desc" style="min-height: 100px;" maxlength="300">{{ old('meta_desc') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Keyword</label>
                                <input value="{{ old('meta_keyword') }}" class="form-control" type="text" name="meta_keyword" maxlength="191">
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
                          Publish <a data-toggle="collapse" href="#post-status"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-status" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Post Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Date Published</label>
                                <div class="input-group input-append date datetimepicker">
                                    <input class="form-control" size="16" type="text" value="immediately" name="published_date" readonly>
                                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
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
                                    <select name="category_parent" class="form-control category-parent myselect2" style="width: 100%;"></select>
                                </div>
                                <button class="btn btn-default add_category_button" type="button">Add New Category</button>
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
                            <select id="mytag" name="tags[]" class="mytag form-control" multiple>
                                @foreach ($alltag as $tag)
                                    <option {{ is_array(old('selected_tag')) && in_array($tag->id, old('$selected_tag')) ? 'selected' : ''}} value="{{$tag->name}}" >{{$tag->name}}</option>
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
                            <a id="browse_fimg_post" data-tujuan="featured_img" data-toggle="modal" data-target="#myFimg" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Set Featured Image</a>
                            <input type="hidden" name="featured_image" id="featured_img" value="{{ old('featured_image') }}">
                            <div class="preview-fimg-wrap" style="display: {{ old('featured_image') != '' ? 'block' : ''  }};">
                                <div class="preview-fimg" style="background-image: url({{ old('featured_image') }});"></div>
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
<div class="overlay"></div>

<div class="custom-modal media-modal">
<div class="close-modal" id="close_media_post" data-toggle="modal" data-target="#myModal">X</div>
    
    <div class="card">
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
            <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple>
            </form>
        </div>
    <div class="card-content table-responsive">
        <table style="width: 100%;" class="table mediatable" id="MediaPost">
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

<div class="custom-modal fimg-modal">
<div class="close-modal" id="close_fimg_post" data-toggle="modal" data-target="#myFimg">X</div>
    <div class="card">
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadfimg').click();">Upload media +
            <form id="actuploadfimg" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadfimg" name="media[]" style="cursor: pointer;display: none;" multiple>
            </form>
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

<div class="custom-modal file-modal">
<div class="close-modal" id="close_file_post" data-toggle="modal" data-target="#myFile">X</div>
    <div class="card">
       <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('fileUpload').click();">Upload file +
            <form id="fileupload-form" method="post" action="" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="fileUpload" name="fileUpload[]" style="cursor: pointer;display: none;" multiple>
            </form>
        </div>
        <div class="card-content table-responsive">
            <table style="width: 100%;" class="table mediatable" id="postFile">
                <thead >
                    <th>Name</th>
                    <th>Label</th>
                    <th>Action</th>
                    <th>Created At</th>
                </thead>
            </table>
        </div>
        <div id="selected-files" style="text-align: left;display: none;">
            <button style="padding: 8px 14px;" id="select-files" class="btn btn-round btn-fill btn-success">Select Files</button>
        </div>
    </div>
</div>
@endsection