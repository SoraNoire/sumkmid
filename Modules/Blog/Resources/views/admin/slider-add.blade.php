@extends('blog::layouts.master')

@section('content')
<div class="col-md-12 admin-row-child">
	<div class="card">
		@if (in_array('write', app()->OAuth::can('panel.slider')))
		<a href="{{ url(route('panel.slider__add')) }}" class="btn btn-round btn-fill btn-info"> New Slider +</a>
		@endif

	    <div class="card-header" data-background-color="green">
	        <h4 class="title">New Slider</h4>
	    </div>
	    <div class="card-content">
	        <form method="post" action="{{ url(route('panel.slider__save')) }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                <div class="col-md-6">
	                	<div class="form-group">
							<label class="control-label">Title</label>
							<textarea name="title" class="form-control simple-tinymce">{{ old('title') }}</textarea>
						</div>
						<div class="form-group">
							<label class="control-label">Description</label>
							<textarea name="description" class="form-control simple-tinymce">{{ old('description') }}</textarea>
						</div>
						<div class="form-group">
							<label class="control-label">Button Text</label>
							<input id="text_btn" name="btn_text" type="text" class="form-control" value="{{ old('btn_text') }}">
							<label class="control-label">Button URL</label>
							<input id="link_btn" name="btn_link" type="url" class="form-control" value="{{ old('btn_link') }}">
						</div>
	                </div>
	            	<div class="col-md-6">
	            		<label style="display: block;">Image Slider</label>
            		 	<a id="browse_fimg_post" data-tujuan="featured_img" data-toggle="modal" data-target="#myFimg" class="btn btn-round btn-fill btn-default {{ in_array('read', app()->OAuth::can('panel.media')) ? '':'disabled' }}" style="margin-bottom: 10px;">Browse Image</a>
                        <input type="hidden" name="slider_img" id="featured_img" value="{{ old('slider_img') }}">
                        <div id="slider" class="preview-fimg-wrap form-group" style="display: {{ old('slider_img') != '' ? 'block' : ''  }};">
                            <div class="preview-fimg" style="background-image: url({{ old('slider_img') }});"></div>
                        </div>
	            	</div>
	            	<div class="col-md-12">
			            <button type="submit" class="btn btn-success pull-left">Save</button>
	            	</div>
	            </div>
	            <div class="clearfix"></div>
	        </form>
	    </div>
	</div>
	</div>
@endsection

@if (in_array('read', app()->OAuth::can('panel.media')))
@section('modal')
<div class="overlay"></div>
<div class="custom-modal fimg-modal">
<div class="close-modal" id="close_media_post" data-toggle="modal" data-target="#myModal">X</div>
    
    <div class="card">
        @if (in_array('write', app()->OAuth::can('panel.media')))
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
            <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple>
            </form>
        </div>
        @endif
    <div class="card-content table-responsive" {{ in_array('write', app()->OAuth::can('panel.media')) ? '':'style=margin-top:30px;' }}>
        <table style="width: 100%;" class="table mediatable" id="sliderImg">
            <thead >
                <th>Preview</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </thead>
        </table>
    </div>
    </div>
</div>
@endsection
@endif