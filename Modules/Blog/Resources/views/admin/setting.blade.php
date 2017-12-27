@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
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
										<label class="control-label">Link Instagram</label>
										<input type="url" name="link_ig" class="form-control" value="{{ $link_ig }}">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Link Youtube</label>
										<input type="url" name="link_yt" class="form-control" value="{{ $link_yt }}">
									</div>

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

		                        	<div class="dd panel-group" id="program-structure">
									    <ol class="dd-list">

									    	<li class="dd-item" data-id="" data-title="" data-description="" data-logo="" data-background="">
												<div class="dd-handle dd3-handle">Drag</div>
												<div class="program-item dd3-content panel panel-default" id="program1">
													<div class="program-title">
														<span>Program 1</span>
														<a data-toggle="collapse" data-parent="#program-structure" href="#program-collapse-1">
															<i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i>
														</a>
													</div>
													<div id="program-collapse-1" class="collapse program-collapse panel panel-default">
														<div class="form-group">
															<label>Title</label>
															<input class="form-control" type="text" name="title" value="">
															<label>Logo</label>
															<div class="input-group">
																<input class="form-control" type="text" name="logo" value="" readonly="readonly">
																<span class="input-group-btn">
			                                                        <button class="btn btn-default" class="program-media" type="button">Browse media</button>
			                                                    </span>
															</div>
															<label>Background</label>
															<div class="input-group">
																<input class="form-control" type="text" name="background" value="" readonly="readonly">
																<span class="input-group-btn">
			                                                        <button class="btn btn-default" class="program-media" type="button">Browse media</button>
			                                                    </span>
															</div>
															<label>Description</label>
															<textarea class="simple-tinymce"></textarea>
														</div>
														<a href="#" class="remove_item">Remove</a>
													</div>
												</div>
											</li>	

									    </ol>
									</div>

		                        </div>
		                    </div>
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


