@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('panel.category__delete__mass') }}" accept-charset="UTF-8">
    @if ( in_array('write', app()->OAuth::can('panel.category')) )
    <a href="{{ route('panel.category__add') }}" class="btn btn-round btn-fill btn-info">New Category +<div class="ripple-container"></div></a>
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    @if ( in_array('delete', app()->OAuth::can('panel.category')) )
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span id="bulk-delete-count"></span> Category</button>
    @endif
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Category</h4>
        <p class="category">All Category</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table {{ in_array('delete', app()->OAuth::can('panel.category')) ? 'mydatatable':'' }} " id="table-categories">
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