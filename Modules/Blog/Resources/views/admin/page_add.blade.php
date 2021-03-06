@extends('blog::layouts.master')

@section('content')

<div class="col-md-12">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable ">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        There is some error. Please check again
    </div>
    @endif

    <form id="post-form" method="post" action="{{ route('panel.page__save') }}" accept-charset="UTF-8">
        <div class="row">         
            <div class="col-md-9"> 
                <h4 class="title">News Page</h4>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <button type="submit" class="btn btn-success pull-right">Save Page</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
        
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="control-label">Title</label>
                    @if ($errors->has('title'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    </div>
                    @endif
                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="Enter Title Here" required="required">
                </div>
                @if ( in_array('read', app()->OAuth::can('panel.media')) )
                <a id="browse_media_post" data-toggle="modal" data-target="#myMedia" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Add Media</a>
                @endif
                
                <div class="form-group">
                    <label class="control-label">Page Content</label>
                    @if ($errors->has('content'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    </div>
                    @endif
                    <textarea class="form-control mytextarea" name="content">{{ old('content') }}</textarea>
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
                                <label>Page Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Draft</option>
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

                @if ( count($templates) > 0 )
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Page Template <a data-toggle="collapse" href="#post-template"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-template" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <select name="page_template" class="form-control">
                                    <option value="none" {{ old('page_template') == 'none' ? 'selected' : '' }}>None</option>
                                    @foreach ($templates as $template)
                                    <option value="{{ $template->file_name }}" {{ old('page_template') == $template->file_name ? 'selected':'' }}>{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if ( in_array('read', app()->OAuth::can('panel.media')) )
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Featured Image <a data-toggle="collapse" href="#post-fimg"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-fimg" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <a id="browse_fimg_post" data-tujuan="featured_img" data-toggle="modal" data-target="#myFimg" class="btn btn-round btn-fill btn-default {{ in_array('read', app()->OAuth::can('panel.media')) ? '':'disabled' }}" style="margin-bottom: 10px;">Set Featured Image</a>
                            <input type="hidden" name="featured_image" id="featured_img" value="{{ old('featured_image') }}">
                            <div class="preview-fimg-wrap" style="display: {{ old('featured_image') != '' ? 'block' : ''  }};">
                                <div class="preview-fimg" style="background-image: url({{ old('featured_image') }});"></div>
                                <a href="#" onclick="remove_fimg()" class="remove-fimg"><i class="fa fa-times" aria-hidden="true"></i> Remove Featured Image</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>


    </form>

</div>
@stop

@if ( in_array('read', app()->OAuth::can('panel.media')) )
@section('modal')
<div class="overlay"></div>

<div class="custom-modal media-modal">
<div class="close-modal" id="close_media_post" data-toggle="modal" data-target="#myModal">X</div>
    
    <div class="card">
        @if (in_array('write', app()->OAuth::can('panel.media')))
        <div class="btn btn-round btn-fill btn-info {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'disabled' }}" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
            <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'disabled' }}>
            </form>
        </div>
        @endif
    <div class="card-content table-responsive" {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'style=margin-top:30px;' }}>
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
        @if (in_array('write', app()->OAuth::can('panel.media')))
        <div class="btn btn-round btn-fill btn-info {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'disabled' }}" style="margin-bottom: 10px;" onclick="document.getElementById('uploadfimg').click();">Upload media +
            <form id="actuploadfimg" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadfimg" name="media[]" style="cursor: pointer;display: none;" multiple {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'disabled' }}>
            </form>
        </div>
        @endif
        <div class="card-content table-responsive" {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'style=margin-top:30px;' }}>
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
@endsection
@endif