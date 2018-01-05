@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Category</h4>
        <p class="category">{{ $act }} Category</p>
    </div>

    <form method="post" action="{{  ($isEdit) ? route('panel.category__update',$category_id) : route('panel.category__save') }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control" type="text" name="name" value="{{ $name }}" required="required">
        </div>
        <div class="form-group">
            <label class="control-label">Description</label>
            <input class="form-control" type="text" name="description" value="{{ $desc }}">
        </div>
        <div class="form-group">
            <label class="control-label">Parent</label>
            <select name="parent" class="form-control myselect2">
                {!! $allparent !!}
            </select>
        </div>
        <button type="submit" class="btn btn-success pull-right">Save</button>
        @if ($act == 'Edit' && in_array('delete', app()->OAuth::can('panel.category')))
        <a style="margin-right: 10px;" href="{{ route('panel.category__delete', $category->id) }}" class="btn btn-danger pull-right" onclick="return confirm('Delete Category?');">Delete</a>
        @endif
    </form>
</div>
</div>
@stop

