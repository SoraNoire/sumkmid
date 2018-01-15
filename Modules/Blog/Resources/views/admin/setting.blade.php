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
		                          Homepage <a data-toggle="collapse" href="#setting-homepage"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		                        </h4>
		                    </div>
		                    <div id="setting-homepage" class="panel-collapse collapse in">
		                        <div class="panel-body">

		                        	<div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          About Us <a data-toggle="collapse" href="#setting-about-us"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-about-us" class="panel-collapse collapse in">
					                        <div class="panel-body">

					                        	<div class="form-group">
					                        		<label>Title</label>
					                        		<input type="text" name="about_title" class="form-control" value="{{ $about_us->title ?? '' }}">
					                        	</div>

				            					<div class="form-group">
				            						<label>About Us</label>
					                        		<textarea class="form-control" name="about_us">{{ $about_us->text ?? '' }}</textarea>
					                        	</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>   

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Post <a data-toggle="collapse" href="#setting-home-post"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-home-post" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
				            						<label>Section Title</label>
				            						<input type="text" name="post_title" class="form-control" value="{{ $post->title ?? '' }}">
												</div>

												<div class="form-group">
													<small>Use Gallery</small> 
												    <label class="switch">
													  <input id="post_check" type="checkbox" name="post_check" value="1" {{ $post->check ?? ''}}>
													  <span class="slider round"></span>
													</label>
												</div>

												<div class="form-group">
													<label>Select Category Gallery</label>
													<select id="post_category" class="form-control" name="post_category">
														<option value="0" {{ 0 == $post->category ? 'selected' : '' }}>Latest Gallery</option>
														@foreach( $all_cat as $cat )
														<option value="{{ $cat->id }}" {{ $cat->id == $post->category ? 'selected' : '' }}>{{ $cat->name }}</option>
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

					                        	<div class="form-group">
					                        		<label>Section Title</label>
					                        		<input type="text" name="program_title" class="form-control" value="{{ $program->title ?? '' }}">
					                        	</div>
					                        	<div class="form-group">
					                        		<label>Section Description</label>
					                        		<textarea name="program_desc" class="form-control">{{ $program->desc ?? '' }}</textarea>
					                        	</div>
					                        	<div class="form-group">
					                        		<label>Section Button Label</label>
					                        		<input type="text" name="program_button" class="form-control" value="{{ $program->button ?? '' }}">
					                        	</div>
					                        	<div class="form-group">
					                        		<label>Section Button URL</label>
					                        		<input type="text" name="program_url" class="form-control" value="{{ $program->url ?? '' }}">
					                        	</div>

				            					<button type="button" class="btn btn-info pull-left add-program">Add Program +</button>
			            						<div class="clearfix"></div>

					                        	<div class="dd panel-group" id="program-structure">
												    <ol class="dd-list">
												    	{!! $list_program !!}
												    </ol>
												</div>

			            						<div class="clearfix"></div>
				            					<button type="submit" class="btn btn-success pull-left save-program" style="margin-top: 10px;">Save</button>

					                        </div>
					                    </div>
					                </div>

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Socmed Feed <a data-toggle="collapse" href="#setting-socmed-feed"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-socmed-feed" class="panel-collapse collapse in">
					                        <div class="panel-body">
					                        	
					                        	<div class="form-group">
													<label>Title</label>
													<input type="text" name="socfeed_title" value="{{ $socfeed->title ?? '' }}" class="form-control">
												</div>

				            					<div class="form-group">
													<label>Instagram Token</label>
													<input type="text" name="instagram_token" value="{{ $instagram_token }}" class="form-control">
												</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

					                        </div>
					                    </div>
					                </div>    

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                          Mentor <a data-toggle="collapse" href="#setting-mentor"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-mentor" class="panel-collapse collapse in">
					                        <div class="panel-body">
					                        	
					                        	<div class="form-group">
													<label>Title</label>
													<input type="text" name="mentor_title" value="{{ $mentor->title ?? '' }}" class="form-control">
												</div>

	            								<button type="submit" class="btn btn-success pull-left">Save</button>

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

					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                           Homepage Metas <a data-toggle="collapse" href="#setting-meta"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
					                        </h4>
					                    </div>
					                    <div id="setting-meta" class="panel-collapse collapse in">
					                        <div class="panel-body">

				            					<div class="form-group">
				            						<label>Meta Title</label>
				            						<input type="text" name="meta_title" class="form-control" value="{{  }}">
												</div>
												<div class="form-group">
				            						<label>Meta Title</label>
				            						<textarea name="meta_desc" class="form-control">{{  }}</textarea>
												</div>
												<div class="form-group">
				            						<label>Meta Title</label>
				            						<input type="text" name="meta_keyword" class="form-control" value="{{  }}">
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
										<label class="control-label">Google Tag Manager ID</label>
										<input type="text" name="gtag_manager" class="form-control" value="{{ $gtag_manager }}">
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

@if (in_array('read', app()->OAuth::can('panel.media')))
@section('modal')
<div class="overlay"></div>

<div class="custom-modal media-modal">
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
@endif