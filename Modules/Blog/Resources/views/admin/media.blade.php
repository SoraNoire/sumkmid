@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<h3>Media</h3>
<h5>Tambah Media</h5>
@if ( in_array('write', app()->OAuth::can('panel.media')) )
<div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
    <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act-new-media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple>
    </form>
</div>
@endif
@if ( in_array('delete', app()->OAuth::can('panel.media')) )
<form class="pull-right" method="post" action="{{ URL::to($prefix.'bulk-delete-media/')}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;margin-bottom: 10px;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Media</button>
</form>
<a id="canceldelete" style="margin-bottom: 10px;display: none;" onclick="cancelDelete()" class="btn btn-round btn-fill btn-danger pull-right">Cancel Delete</a>
@endif
<div class="card">
    <div class="card-content table-responsive">
    <div class="table-overlay">Processing...</div>
        <table class="table {{ in_array('delete', app()->OAuth::can('panel.media')) ? 'mydatatable':'' }} mediatable" id="MediaTable">
            <thead>
                <th>Preview</th>
                <th>Name</th>
                <th>Created At</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop
 

