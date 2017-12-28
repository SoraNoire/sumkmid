@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Tag</h4>
        <p class="category">{{ $act }} Tag</p>
    </div>

    <form method="post" action="{{ ($isEdit) ? route('panel.tag__update',$id) : route('panel.tag__save') }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control" type="text" name="name" value="{{ $name }}">
        </div>
        <button type="submit" class="btn btn-success pull-right">Save</button>
        @if ($act == 'Edit')
        <a style="margin-right: 10px;" href="{{ route('panel.tag__delete',$id) }}" class="btn btn-danger pull-right" onclick="return confirm('Delete Tag?');">Delete</a>
        @endif
    </form>
</div>
</div>
@stop
