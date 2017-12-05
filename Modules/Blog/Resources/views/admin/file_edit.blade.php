@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Edit File</h4>
    </div>

    <form method="post" action="{{ route('updatefile',$id) }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label">Label</label>
            <input class="form-control" type="text" name="label" value="{{ $label }}">
        </div>
        <button type="submit" class="btn btn-success pull-right">Save</button>
    </form>
</div>
</div>
@stop
