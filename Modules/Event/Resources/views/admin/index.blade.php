@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('panel.event__delete__mass') }}" accept-charset="UTF-8">
@if (in_array('write', app()->OAuth::can('panel.event')))
<a href="{{ route('panel.event__add') }}" class="btn btn-round btn-fill btn-info">New Event +<div class="ripple-container"></div></a>
@endif

<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" class="bulk-delete-id">
@if (in_array('delete', app()->OAuth::can('panel.event')))
<button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Event</button>
@endif
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Events</h4>
        <p class="category">All Event</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table {{ in_array('delete', app()->OAuth::can('panel.event')) ? 'mydatatable' : '' }}" id="event-table">
            <thead>
                <th>Title</th>
                <th>Author</th>
                <th>Published At</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop
