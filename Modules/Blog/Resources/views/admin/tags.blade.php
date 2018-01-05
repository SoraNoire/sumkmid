@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('panel.tag__delete__mass') }}" accept-charset="UTF-8">
    @if (in_array('write', app()->OAuth::can('panel.tag')))
    <a href="{{ route('panel.tag__add') }}" class="btn btn-round btn-fill btn-info">New Tag +<div class="ripple-container"></div></a>
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    @if (in_array('delete', app()->OAuth::can('panel.tag')))
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Tag</button>
    @endif
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Tag</h4>
        <p class="category">All Tag</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="table-tags">
            <thead>
                <th>Name</th>
                <th>Created At</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop

