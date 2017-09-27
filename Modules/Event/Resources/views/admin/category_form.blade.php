@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">

@if(session('msg'))
<div class="alert alert-{{ session('status') }}">
  {{ session('msg') }}
</div>
@endif

<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Event Category</h4>
        <p class="category">{{ $act }} Category</p>
    </div>

    <form method="post" action="{{ url($action) }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control" type="text" name="name" value="{{ $name }}">
        </div>
        <button type="submit" class="btn btn-success pull-right">Save</button>
        @if ($act == 'edit')
        <a style="margin-right: 10px;" href="{{ url($prefix.'delete-category/'.$category->id) }}" class="btn btn-danger pull-right" onclick="return confirm('Delete Category?');">Delete</a>
        @endif
    </form>
</div>
</div>
@stop
