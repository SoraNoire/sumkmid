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
    <h4 class="title">Edit Mentoring</h4>

    <form id="post-form" method="post" action="{{ route('panel.mentoring__update',$post->id) }}" accept-charset="UTF-8">
        <a href="{{ route('panel.mentoring__add') }}" class="btn btn-round btn-fill btn-info">
            New Mentoring +<div class="ripple-container"></div>
        </a>
        <!-- <a target="_blank" href="{{ URL::to($prefix.'show/'.$post->slug) }}" class="btn btn-round btn-fill btn-info">
            View Post<div class="ripple-container"></div>
        </a> -->
        <a onclick="return confirm('Delete Mentoring?');" href="{{ route('panel.mentoring__delete', $post->id) }}" class="btn btn-round btn-fill btn-danger">
            Delete Mentoring<div class="ripple-container"></div>
        </a>

        <button type="submit" class="btn btn-success pull-right">Save</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="control-label">Title</label>
                    <input class="form-control" type="text" name="title" value="{{ $post->title }}" placeholder="Enter Title Here" required="required">
                </div>

                <div class="form-group">
                    <label class="control-label">Url Video</label>
                    <input class="form-control" type="url" name="video_url" value="{{ $video_url }}" placeholder="Enter url video here" required="required">
                    <small><b>Contoh</b> : https://www.youtube.com/<b>watch?v=</b>wlsdMpnDBn8 <b>ATAU</b> https://www.youtube.com/<b>embed/</b>wlsdMpnDBn8</small>
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

                            <div class="file-list">
                                @if (count($files) > 0)
                                    @foreach($files as $file)
                                    <div class="form-group input-group file-item">
                                        <span class="input-group-addon">
                                            <i class="fa fa-file-o" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" name="file_label[]" class="form-control" placeholder="insert label for file here" value="{{ $file->file_label }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger file-delete" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                                        </span>
                                        <input type="hidden" name="file_name[]" value="{{ $file->file_name }}">
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Publish Status <a data-toggle="collapse" href="#post-status"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-status" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ $post->status == 0 ? 'selected' : '' }}>Draft</option>
                                </select>
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