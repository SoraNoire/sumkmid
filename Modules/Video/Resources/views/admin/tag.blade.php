@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
@if(session('msg'))
<div class="alert alert-{{ session('status') }}">
  {{ session('msg') }}
</div>
@endif
<form method="post" action="{{ URL::to($prefix.'bulk-delete-tag/')}}" accept-charset="UTF-8">
    <a href="{{ URL::to($prefix.'create-tag') }}" class="btn btn-round btn-fill btn-info">New Tag +<div class="ripple-container"></div></a>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Tag</button>
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Tag</h4>
        <p class="category">All Tag</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table mydatatable" id="VideoTagTable">
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

