@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card" id="site-setting">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Site Setting</h3>
	    </div>
	    <div class="card-content">
	        <form method="post" action="{{ route('panel.setting.site__update') }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                <div class="col-md-12">

	                	<div class="panel panel-default">
		                    <div class="panel-heading">
		                        <h4 class="panel-title">
		                          Homepage <a data-toggle="collapse" href="#setting-analytics"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		                        </h4>
		                    </div>
		                    <div id="setting-analytics" class="panel-collapse collapse in">
		                        <div class="panel-body">

		                        	<div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Video Section <a data-toggle="collapse" href="#setting-video"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-video" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
					                        		<label>Title</label>
					                        		<input type="text" class="form-control" name="video_title" value="{{ $video->title ?? '' }}">
					                        		<label>Description</label>
					                        		<textarea class="form-control" name="video_description"> {{ $video->description ?? '' }}</textarea>
					                        		<label>Button Text</label>
					                        		<input type="text" class="form-control" name="video_button"  value="{{ $video->button ?? '' }}"></input>
					                        		<label>Button Link</label>
					                        		<input type="text" class="form-control" name="video_button_link" value="{{ $video->button_link ?? '' }}"></input>
					                        		<label>Video Link</label>
					                        		<input type="text" class="form-control" name="video_link"  value="{{ $video->link ?? '' }}"></input>
					                        		<label>Video Background</label>
					                        		<div class="input-group">
					                        			<input class="form-control" type="text" name="video_bg" value="{{ $video->background }}" readonly="readonly" id="video-bg">
					                        			<span class="input-group-btn">
					                        				<button class="btn btn-default program-media" type="button" data-tujuan="video-bg">Browse media</button>
					                        			</span>
					                        		</div>
					                        	</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>  

		                        	<div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Quote Section <a data-toggle="collapse" href="#setting-quote"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-quote" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
					                        		<label>Title</label>
					                        		<input type="text" class="form-control" name="quote_title" value="{{ $quote->title ?? '' }}">
					                        		<label>Description</label>
					                        		<textarea class="form-control" name="quote_description"> {{ $quote->description ?? '' }}</textarea>
					                        		<label>Button Text</label>
					                        		<input type="text" class="form-control" name="quote_button" value="{{ $quote->button ?? '' }}"></input>
					                        		<label>Button Link</label>
					                        		<input type="text" class="form-control" name="quote_button_link" value="{{ $quote->button_link ?? '' }}"></input>
					                        	</div>	

					                        	<div class="form-group">
					                        		<label>Quote</label>
					                        		<textarea class="form-control" name="quote_text">{{ $quote->text ?? '' }}</textarea>
					                        		<label>Quote From</label>
					                        		<input type="text" class="form-control" name="quote_from" value="{{ $quote->from ?? '' }}"></input>
					                        	</div>	

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>   

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Home Gallery <a data-toggle="collapse" href="#setting-gallery-cat"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-gallery-cat" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
													<label>Select Category Gallery</label>
													<select class="form-control" name="gallery_category">
														@foreach( $all_cat as $cat )
														<option value="{{ $cat->id }}" {{ $cat->id == $gallery_category ? 'selected' : '' }}>{{ $cat->name }}</option>
														@endforeach
													</select>
												</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>   

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Program <a data-toggle="collapse" href="#setting-program"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-program" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<button type="button" class="btn btn-info pull-left add-program">Add Program +</button>
			            						<div class="clearfix"></div>

					                        	<div class="dd panel-group" id="program-structure">
												    <ol class="dd-list">
												    	{!! $list_program !!}
												    </ol>
												</div>

			            						<div class="clearfix"></div>
				            					<button type="button" class="btn btn-success pull-left save-program" style="margin-top: 10px;">Save</button>

					                        </div>
					                    </div>
					                </div>

		                        	<div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Footer <a data-toggle="collapse" href="#setting-footer"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-footer" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
													<label>Footer Description</label>
													<textarea class="form-control" name="footer_desc">{{ $footer_desc ?? '' }}</textarea>
												</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>    

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Email <a data-toggle="collapse" href="#setting-email"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-email" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
				            						<input type="Email" name="email" value="{{ $email }}" class="form-control">
												</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>                     

		                        </div>
		                    </div>
		                </div>

	                	<div class="panel panel-default">
		                    <div class="panel-heading">
		                        <h4 class="panel-title">
		                          Analytics <a data-toggle="collapse" href="#setting-analytics"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		                        </h4>
		                    </div>
		                    <div id="setting-analytics" class="panel-collapse collapse in">
		                        <div class="panel-body">
		                            
									<div class="form-group label-floating">
										<label class="control-label">Google Analytic ID</label>
										<input type="text" name="analytic" class="form-control" value="{{ $analytic }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Facebook Pixel ID</label>
										<input type="text" name="fb_pixel" class="form-control" value="{{ $fb_pixel }}">
									</div>

	            					<button type="submit" class="btn btn-success pull-left">Save</button>

		                        </div>
		                    </div>
		                </div>

		                <div class="panel panel-default">
		                    <div class="panel-heading">
		                        <h4 class="panel-title">
		                          Social Media <a data-toggle="collapse" href="#setting-socmed"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		                        </h4>
		                    </div>
		                    <div id="setting-socmed" class="panel-collapse collapse in">
		                        <div class="panel-body">
		                            
									<div class="form-group label-floating">
										<label class="control-label">Link Facebook</label>
										<input type="url" name="link_fb" class="form-control" value="{{ $link_fb }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link Twitter</label>
										<input type="url" name="link_tw" class="form-control" value="{{ $link_tw }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link LinkedIn</label>
										<input type="url" name="link_in" class="form-control" value="{{ $link_in }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link Instagram</label>
										<input type="url" name="link_ig" class="form-control" value="{{ $link_ig }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link Google+</label>
										<input type="url" name="link_gplus" class="form-control" value="{{ $link_gplus }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link Youtube</label>
										<input type="url" name="link_yt" class="form-control" value="{{ $link_yt }}">
									</div>

	            					<button type="submit" class="btn btn-success pull-left">Save</button>

		                        </div>
		                    </div>
		                </div>
						
	                </div>
	            </div>
	            <div class="clearfix"></div>
	        </form>
	    </div>
	</div>
</div>

@endsection


@section('modal')
<div class="overlay"></div>

<div class="custom-modal media-modal">
<div class="close-modal" id="close_media_post" data-toggle="modal" data-target="#myModal">X</div>
    
    <div class="card">
        <div class="btn btn-round btn-fill btn-info" style="margin-bottom: 10px;" onclick="document.getElementById('uploadmedia').click();">Upload media +
            <form id="actuploadmedia" method="post" action="{{ URL::to('/administrator/act_new_media') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="uploadmedia" name="media[]" style="cursor: pointer;display: none;" multiple>
            </form>
        </div>
    <div class="card-content table-responsive">
        <table style="width: 100%;" class="table mediatable" id="programMedia">
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
