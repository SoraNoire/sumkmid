@extends('blog::layouts.master')

@section('content')
<script> eventId = {{$event->id ?? 0}}</script>
<div class="col-md-12">
    
    <h4 class="title">Edit events</h4>

    <form id="event-form" method="post" action="{{ route('updateevent',$id) }}" accept-charset="UTF-8">
        <a href="{{ route('addevent')}}" class="btn btn-round btn-fill btn-info">
            New Event +<div class="ripple-container"></div>
        </a>
        <a target="_blank" href="{{ URL::to($prefix.'show/'.$event->slug) }}" class="btn btn-round btn-fill btn-info">
            View Event<div class="ripple-container"></div>
        </a>
        <a onclick="return confirm('Delete Event?');" href="{{route('removeevent',$event->id)}}" class="btn btn-round btn-fill btn-danger">
            Delete Event<div class="ripple-container"></div>
        </a>

        <button type="submit" class="btn btn-success pull-right">Save Event</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
                    <input class="form-control" type="text" name="title" value="{{ $title }}" placeholder="Enter Title Here">
                </div>

                <a id="browse_media_post" data-toggle="modal" data-target="#myMedia" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Add Media</a>

                <div class="form-group">
                    <label class="control-label">Event Description</label>
                    @if ($errors->has('description'))
                    <div class="has-error">
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    </div>
                    @endif
                    <textarea class="form-control mytextarea" name="description">{{ $description }}</textarea>
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
                                    <option value="offline" {{ $event_type == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="online" {{ $event_type == 'online' ? 'selected' : '' }}>Online</option>
                                </select>
                            </div>

                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">Tempat</label>
                                <input value="{{ $location }}" class="form-control" type="text" name="location">
                            </div>
                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">URL Google Maps</label>
                                <input value="{{ $gmaps_url }}" class="form-control" type="url" name="gmaps_url">
                            </div>
                            <div class="form-group event-type-offline" style="display: none;">
                                <label class="control-label">HTM</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input value="{{ $htm }}" class="form-control" type="text" name="htm">
                                </div>
                            </div>
                            <div class="form-group event-type-online">
                                <label>URL</label>
                                <input class="form-control" type="url" name="event_url" value="{{ old('event_url') }}">
                            </div>
                            <div class="form-group">
                               <label class="control-label">Select Mentor</label>
                                <select name="mentor[]" class="form-control myselect2" multiple>
                                   @foreach ($mentors as $mentor)
                                        <option value="{{ $mentor->id }}" {{ is_array($selected_mentors) && in_array($mentor->id, $selected_mentors) ? 'selected' : ''}}>{{ $mentor->name }}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="control-label">Open at</label>
                                    <div class="input-group input-append date datetimepicker">
                                        <input class="form-control" size="16" type="text" value="{{ $open_at }}" name="open_at" readonly>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Closed at</label>
                                    <div class="input-group input-append date datetimepicker">
                                        <input class="form-control" size="16" type="text" value="{{ $closed_at }}" name="closed_at" readonly>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
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
                          Publish <a data-toggle="collapse" href="#event-status"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="event-status" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Event Status</label>
                                <select name="status" class="form-control">
                                    <option {{ $status == 1 ? 'selected' : '' }} value="1">Published</option>
                                    <option {{ $status == 0 ? 'selected' : '' }} value="0">Draft</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Date Published</label>
                                <div class="input-group input-append date datetimepicker">
                                    <input class="form-control" size="16" type="text" value="{{ $published_date ?? '' }}" name="published_date" readonly>
                                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                          Featured Image <a data-toggle="collapse" href="#event-fimg"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="event-fimg" class="panel-collapse collapse in">
                        <div class="panel-body form-group">
                            <a id="browse_fimg_post" data-toggle="modal" data-target="#myFimg" class="btn btn-round btn-fill btn-default" style="margin-bottom: 10px;">Set Featured Image</a>
                            <input type="hidden" name="featured_image" id="featured_img" value="{{ $featured_image }}">
                            <div class="preview-fimg-wrap" style="display: {{ $featured_image != '' ? 'block' : ''  }};">
                                <div class="preview-fimg" style="background-image: url({{ $featured_image }});"></div>
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
@endsection