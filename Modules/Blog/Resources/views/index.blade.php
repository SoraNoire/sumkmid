@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
@if(session('msg'))
<div class="alert alert-{{ session('status') }}">
  {{ session('msg') }}
</div>
@endif
<form method="post" action="{{ URL::to('blog/bulk-delete-post/')}}" accept-charset="UTF-8">
<a href="{{ URL::to('blog/create-post') }}" class="btn btn-round btn-fill btn-info">New Post +<div class="ripple-container"></div></a>

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
        <table class="table mydatatable" id="myTableNews">
            <thead>
                <th>Title</th>
                <th>Author</th>
                <th>Created At</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop
