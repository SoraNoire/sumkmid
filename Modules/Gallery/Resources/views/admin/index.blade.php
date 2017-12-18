@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('massdeletegallery') }}" accept-charset="UTF-8">
<a href="{{ route('addgallery') }}" class="btn btn-round btn-fill btn-info">+ Images Gallery<div class="ripple-container"></div></a>
<a href="{{ route('addVideo') }}" class="btn btn-round btn-fill btn-info">+ Video Gallery<div class="ripple-container"></div></a>

<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" class="bulk-delete-id">
<button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Gallery</button>
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Gallery</h4>
        <p class="category">All Gallery</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="table-gallery">
            <thead>
                <th>Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>Published At</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop
