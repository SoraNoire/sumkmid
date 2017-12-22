@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Site Setting</h3>
	    </div>
	    <div class="card-content">
	        <form method="post" action="{{ route('save_setting') }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                <div class="col-md-12">
	                	<h4>Analytics</h4>
						<div class="form-group label-floating">
							<label class="control-label">Google Analytic ID</label>
							<input type="text" name="analytic" class="form-control" value="{{ $analytic }}">
						</div>

						<div class="form-group label-floating">
							<label class="control-label">Facebook Pixel ID</label>
							<input type="text" name="fb_pixel" class="form-control" value="{{ $fb_pixel }}">
						</div>

						<h4>Social Media</h4>
						<div class="form-group label-floating">
							<label class="control-label">Link Facebook</label>
							<input type="url" name="link_fb" class="form-control" value="{{ $link_fb }}">
						</div>

						<div class="form-group label-floating">
							<label class="control-label">Link Twitter</label>
							<input type="url" name="link_tw" class="form-control" value="{{ $link_tw }}">
						</div>

						<div class="form-group label-floating">
							<label class="control-label">Link Instagram</label>
							<input type="url" name="link_ig" class="form-control" value="{{ $link_ig }}">
						</div>

						<div class="form-group label-floating">
							<label class="control-label">Link Youtube</label>
							<input type="url" name="link_yt" class="form-control" value="{{ $link_yt }}">
						</div>
	                </div>
	            </div>
	            <button type="submit" class="btn btn-success pull-left">Simpan</button>
	            <div class="clearfix"></div>
	        </form>
	    </div>
	</div>
</div>

@endsection


