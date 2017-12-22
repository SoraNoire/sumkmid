@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('panel.video__delete__mass') }}" accept-charset="UTF-8">
<a href="{{ route('panel.video__add') }}" class="btn btn-round btn-fill btn-info">New Video +<div class="ripple-container"></div></a>

<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" class="bulk-delete-id">
<button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Video</button>
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Videos</h4>
        <p class="category">All Video</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="table-videos">
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
