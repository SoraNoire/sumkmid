@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
<h3>Files</h3>
<div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('fileUpload').click();">Upload file +
    <form id="fileupload-form" method="post" action="" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="file" id="fileUpload" name="fileUpload[]" style="cursor: pointer;display: none;" multiple>
    </form>
</div>
<form class="pull-right" method="post" action="{{ route('panel.file__delete__mass') }}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" class="bulk-delete-id">
    <button type="sumbit" style="display: none;margin-bottom: 10px;" class="btn btn-round btn-fill btn-danger bulk-delete-item">Delete <span class="bulk-delete-count"></span> Files</button>
</form>
<a id="canceldelete" style="margin-bottom: 10px;display: none;" onclick="cancelDelete()" class="btn btn-round btn-fill btn-danger pull-right">Cancel Delete</a>
<div class="card">
    <div class="card-content table-responsive">
    <div class="table-overlay">Processing...</div>
        <table class="table mydatatable mediatable" id="filesTable">
            <thead>
                <th>Name</th>
                <th>Lable</th>
                <th>Action</th>
                <th>Created At</th>
            </thead>
        </table>
    </div>
</div>
</div>
@stop


