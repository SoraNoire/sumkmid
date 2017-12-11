@extends('blog::layouts.master')

@section('content')
<script> eventId = {{$event->id ?? 0}}</script>
<div class="col-md-12">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable ">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        There is some error. Please check again
    </div>
    @endif
    <h4 class="title">Edit events</h4>

    <form id="event-form" method="post" action="{{ route('updateevent',$id) }}" accept-charset="UTF-8">
        <a href="{{ route('addevent')}}" class="btn btn-round btn-fill btn-info">
            New Event +<div class="ripple-container"></div>
        </a>
        <!-- <a target="_blank" href="{{ URL::to($prefix.'show/'.$event->slug) }}" class="btn btn-round btn-fill btn-info">
            View Event<div class="ripple-container"></div>
        </a> -->
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
                            <strong>This field is required</strong>
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
                            <strong>This field is required</strong>
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
                                <div class="form-group">
                                    <small>Free</small> 
                                    <label class="switch">
                                      <input type="checkbox" name="htm_free" value="free" {{ $htm == 'free' ? 'checked' : '' }}>
                                      <span class="slider round"></span>
                                    </label>
                                </div>
                                <div id="htm-parent">
                                    @if ( is_array($htm) && count($htm) > 0 )
                                        @foreach ($htm as $htm)
                                        <div class="row" id="htm-1" data-id="1">
                                            <div class="form-group col-sm-6">
                                                <label>Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp</span>
                                                    <input value="{{ $htm->nominal }}" class="form-control" type="text" name="htm_nominal[]">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Label</label>
                                                <div class="input-group">
                                                    <input value="{{ $htm->label }}" type="text" name="htm_label[]" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info" class="add-htm" onclick="add_htm()" type="button">+</button>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-warning" class="remove-htm" onclick="remove_htm('htm-1')" type="button">-</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                    <div class="row" id="htm-1" data-id="1">
                                        <div class="form-group col-sm-6">
                                            <label>Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input value="" class="form-control" type="text" name="htm_nominal[]">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Label</label>
                                            <div class="input-group">
                                                <input type="text" name="htm_label[]" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info" class="add-htm" onclick="add_htm()" type="button">+</button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-warning" class="remove-htm" onclick="remove_htm('htm-1')" type="button">-</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group event-type-online">
                                <label>URL</label>
                                <input class="form-control" type="url" name="event_url" value="{{ old('event_url') }}">
                            </div>
                            <div class="form-group">
                               <label class="control-label">Select mentor that registered on MDirect</label>
                                <select name="mentor_registered[]" class="form-control myselect2" multiple>
                                   @foreach ($mentors as $mentor)
                                        <option value="{{ $mentor->id }}" {{ is_array($mentor_registered) && in_array($mentor->id, $mentor_registered) ? 'selected' : ''}}>{{ $mentor->name }}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                               <label class="control-label">Input mentor that <span style="color: #d9534f;">not</span> registered on MDirect</label>
                                <select name="mentor_not_registered[]" class="form-control mytag" multiple>
                                    @foreach ($mentor_not_registered as $mentor_n)
                                    <option value="{{ $mentor_n }}" selected="selected">{{ $mentor_n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Open at</label>
                                <div class="form-inline">
                                    <div class="input-group input-append date event-datetimepicker">
                                        <input class="form-control" size="16" type="text" value="{{ $open_date }}" name="open_date" readonly required>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="number" name="hour_open" min="0" max="23" maxlength="2" value="{{ $hour_open }}" placeholder="HH" class="form-control event_time" required="required">&nbsp;:
                                    <input type="number" name="minute_open" min="0" max="59" maxlength="2" value="{{ $minute_open }}" placeholder="mm" class="form-control event_time" required="required">
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
                                        <input class="form-control" size="16" type="text" value="{{ $closed_date }}" name="closed_date" readonly required>
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="number" name="hour_close" min="0" max="23" maxlength="2" value="{{ $hour_close }}" placeholder="HH" class="form-control event_time" required>&nbsp;:
                                    <input type="number" name="minute_close" min="0" max="59" maxlength="2" value="{{ $minute_close }}" placeholder="mm" class="form-control event_time" required>
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
@endsection