@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<form method="post" action="{{ route('panel.page__delete__mass') }}" accept-charset="UTF-8">
    @if (in_array('write', app()->OAuth::can('panel.page')))
    <a href="{{ route('panel.page__add') }}" class="btn btn-round btn-fill btn-info">New Page +<div class="ripple-container"></div></a>
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    @if (in_array('delete', app()->OAuth::can('panel.page')))
    <button type="sumbit" style="display: none;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Page</button>
    @endif
</form>
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Pages</h4>
        <p class="category">All Pages</p>
    </div>

    <div class="card-content table-responsive">
        <table class="table {{ in_array('delete', app()->OAuth::can('panel.page')) ? 'mydatatable' : '' }}" id="pages-table">
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

