@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
@if(session('msg'))
<div class="alert alert-{{ session('status') }}">
  {{ session('msg') }}
</div>
@endif
<form method="post" action="{{ route('massdeletepage') }}" accept-charset="UTF-8">
    <a href="{{ route('addpage') }}" class="btn btn-round btn-fill btn-info">New Page +<div class="ripple-container"></div></a>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Page</button>
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Pages</h4>
        <p class="category">All Pages</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="pages-table">
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

