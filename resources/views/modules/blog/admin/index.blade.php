@extends('blog::layouts.master')

@section('content')

<div class="col-md-12">
<form method="post" action="{{ route('panel.post__delete__mass') }}" accept-charset="UTF-8">
<a href="{{ route('panel.post__add') }}" class="btn btn-round btn-fill btn-info">New Post +<div class="ripple-container"></div></a>

<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" class="bulk-delete-id">
<button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Post</button>
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Posts</h4>
        <p class="category">All Post</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="posts-table">
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
