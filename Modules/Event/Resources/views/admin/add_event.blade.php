@extends('blog::layouts.master')

@section('content')
<script> eventId = 0</script>
<div class="col-md-12">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable ">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        There is some error. Please check again
    </div>
    @endif
    <h4 class="title">New events</h4>

    <form id="event-form" method="post" action="{{ route('panel.event__save') }}" accept-charset="UTF-8">
        @if (in_array('write', app()->OAuth::can('panel.event')))
        <a href="{{ route('panel.event__add') }}" class="btn btn-round btn-fill btn-info">
            New Event +<div class="ripple-container"></div>
        </a>
        @endif
        <button type="submit" class="btn btn-success pull-right">Save Event</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="control-label">Title</label>
                    @if ($errors->has('title'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>This field is required</strong>
                        </span>
                    </div>
                    @endif
                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="Enter Title Here" required="required">
                </div>

                @if (in_array('read', app()->OAuth::can('panel.media')))
                <a id="browse_media_post" data-toggle="modal" data-target="#myMedia" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Add Media</a>
                @endif

                <div class="form-group">
                    <label class="control-label">Event Description</label>
                    @if ($errors->has('description'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>This field is required</strong>
                        </span>
                    </div>
                    @endif
                    <textarea class="form-control mytextarea" name="description">{{ old('description') }}</textarea>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Event Details <a data-toggle="collapse" href="#event-setting"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="event-setting" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Select Event Type</label>
                                <select id="event-type" name="event_type" class="form-control" onchange="select_event_type()">
                                    <option value="offline" {{ old('event_type') == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="online" {{ old('event_type') == 'online' ? 'selected' : '' }}>Online</option>
                                </select>
                            </div>

                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">Location</label>
                                <input value="{{ old('location') }}" class="form-control" type="text" name="location">
                            </div>
                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">URL Google Maps</label>
                                <input value="{{ old('gmaps_url') }}" class="form-control" type="url" name="gmaps_url">
                            </div>
                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">HTM</label>
                                <div class="form-group">
                                    <small>Free</small> 
                                    <label class="switch">
                                      <input type="checkbox" name="htm_free" value="free" {{ old('htm_free') }}>
                                      <span class="slider round"></span>
                                    </label>
                                </div>
                                <div id="htm-parent">
                                    <div class="row" id="htm-1" data-id="1">
                                        <div class="form-group col-sm-6">
                                            <label>Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input value="{{ old('htm_nominal') }}" class="form-control" type="text" name="htm_nominal[]">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Label</label>
                                            <div class="input-group">
                                                <input value="{{ old('htm_label') }}" type="text" name="htm_label[]" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info" class="add-htm" onclick="add_htm()" type="button">+</button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-warning" class="remove-htm" onclick="remove_htm('htm-1')" type="button">-</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group event-type-online">
                                <label>URL Event</label>
                                <input class="form-control" type="url" name="event_url" value="{{ old('event_url') }}">
                            </div>

                            <div class="form-group">
                               <label class="control-label">Select Mentor</label>
                                <select name="mentor_registered[]" class="form-control myselect2" multiple>
                                   @foreach ($mentors as $mentor)
                                        <option value="{{ $mentor->id }}" {{ old('mentor') == $mentor->id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                               <label class="control-label">Input mentor that <span style="color: #d9534f;">not</span> registered on MDirect</label>
                                <select name="mentor_not_registered[]" class="form-control mytag" multiple>
                                   
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Open at</label>
                                <div class="form-inline">
                                    <div class="input-group input-append date event-datetimepicker">
                                        <input class="form-control" size="16" type="text" value="{{ old('open_date') }}" name="open_date" readonly required>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="number" name="hour_open" id="hour_open" min="0" max="23" maxlength="2" value="{{ old('hour_open') }}" placeholder="HH" class="form-control" required="required">&nbsp;:
                                    <input type="number" name="minute_open" id="minute_open" min="0" max="59" maxlength="2" value="{{ old('minute_open') }}" placeholder="mm" class="form-control" required="required">
                                    <label>WIB</label>
                                </div>
                                @if ($errors->has('open_date'))
                                <div class="has-error">
                                    <span class="help-block">
                                        <strong>This field is required</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Closed at</label>
                                <div class="form-inline">
                                    <div class="input-group input-append date event-datetimepicker">
                                        <input class="form-control" size="16" type="text" value="{{ old('closed_date') }}" name="closed_date" readonly required>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="number" name="hour_close" id="hour_open" min="0" max="23" maxlength="2" value="{{ old('hour_close') }}" placeholder="HH" class="form-control" required>&nbsp;:
                                    <input type="number" name="minute_close" id="minute_open" min="0" max="59" maxlength="2" value="{{ old('minute_close') }}" placeholder="mm" class="form-control" required>
                                    <label>WIB</label>
                                </div>
                                @if ($errors->has('closed_date'))
                                <div class="has-error">
                                    <span class="help-block">
                                        <strong>This field is required</strong>
                                    </span>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          SEO Setting <a data-toggle="collapse" href="#event-seo"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="event-seo" class="panel-collapse collapse in">
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
                          Publish <a data-toggle="collapse" href="#event-status"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="event-status" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Event Status</label>
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

                @if (in_array('read', app()->OAuth::can('panel.media')))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Featured Image <a data-toggle="collapse" href="#post-fimg"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="post-fimg" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <a id="browse_fimg_post" data-tujuan="featured_img" data-tujuan="featured_img" data-toggle="modal" data-target="#myFimg" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Set Featured Image</a>
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

@if (in_array('read', app()->OAuth::can('panel.media')))
@section('modal')
<div class="overlay"></div>

<div class="custom-modal media-modal">
<div class="close-modal" id="close_media_post" data-toggle="modal" data-target="#myModal">X</div>
    
    <div class="card">
        @if (in_array('write', app()->OAuth::can('panel.media')))
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
            <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple>
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
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadfimg').click();">Upload media +
            <form id="actuploadfimg" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadfimg" name="media[]" style="cursor: pointer;display: none;" multiple>
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