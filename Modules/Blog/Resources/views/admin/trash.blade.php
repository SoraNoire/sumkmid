@extends('blog::layouts.master')

@section('content')

<div class="col-md-12">
@if (in_array('delete', app()->OAuth::can('panel.post.trash')))
<a href="{{ route('panel.post.trash__delete__clear') }}" class="btn btn-round btn-fill btn-info">
    Empty Trash<div class="ripple-container"></div>
</a>
@endif
@if (in_array('delete', app()->OAuth::can('panel.post.trash')))
<form method="post" action="{{ route('panel.post.trash__delete__mass') }}" accept-charset="UTF-8" style="display: inline-block;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete Permanent <span class="bulk-delete-count"></span> Post</button>
</form>
@endif
@if (in_array('edit', app()->OAuth::can('panel.post.trash')))
<form method="post" action="{{ route('panel.post.trash__update__restore__mass') }}" accept-charset="UTF-8" style="display: inline-block;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-success bulk-delete-item">Restore <span class="bulk-delete-count"></span> Post</button>
</form>
@endif
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Posts</h4>
        <p class="category">All Post</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="posts-trash">
            <thead>
                <th>Title</th>
                <th>Author</th>
                <th>Post Type</th>
                <th>Published At</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop
