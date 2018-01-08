@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	@if(session('msg'))
	<div class="alert alert-{{ session('status') }}">
	  {{ session('msg') }}
	</div>
	@endif
    <h4 class="title">Menu</h4>
    <p class="category">Edit posisi menu</p>
	    
	<div class="row">
		<div class="col-md-3 panel-group" id="menu_component">

			@if ( in_array('read', app()->OAuth::can('panel.page')) )
			<div id="menu_page" class="panel panel-default">
		      	<div class="panel-heading">
			        <h4 class="panel-title">
			          Page <a data-toggle="collapse" data-parent="#menu_component" href="#collapse1"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
			        </h4>
		      	</div>
		      	<div id="collapse1" class="panel-collapse collapse in">
		        	<div class="panel-body form-group">
		        		<ul class="tab-menu">
					    	<li class="active"><a data-toggle="tab" href="#page_component">Lihat semua</a></li>
					    	<li><a data-toggle="tab" href="#search_page_component">Cari</a></li>
					  	</ul>

					  	<div class="tab-content">
					  		<div id="page_component" class="tab-pane fade in active">
					  			<ul>
				        		@if (count($pages) > 0)
				        		@foreach($pages as $page)
				        		<li>
				        		<label><input type="checkbox" name="menu_page" value="{{ $page->id }}" data-link="{{ '/'.$page->slug }}" data-label="{{ $page->title }}"> {{ $page->title }}</label></li>
				        		@endforeach
				        		@else
				        		<span>No Page Found</span>
				        		@endif
				        		</ul>
				        		<button style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control add_menu">Add to Menu</button>
					  		</div>

    						<div id="search_page_component" class="tab-pane fade">
    							<input type="text" name="search_component" class="form-control">
    							<div class="search-result">
    								<ul>
    								</ul>
				        			<button style="margin-top: 10px;display: none;" class="btn btn-round btn-fill btn-info form-control add_menu">Add to Menu</button>
    							</div>
    						</div>
					  	</div>
		        	</div>
		      	</div>
		    </div>
		    @endif

		    @if ( in_array('read', app()->OAuth::can('panel.category')) )
		    <div id="menu_category" class="panel panel-default">
		      	<div class="panel-heading">
		        	<h4 class="panel-title">
		          	Category <a data-toggle="collapse" data-parent="#menu_component" href="#collapse3"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		        	</h4>
		      	</div>
		      	<div id="collapse3" class="panel-collapse collapse">
		        	<div class="panel-body">

		        		<ul class="tab-menu">
					    	<li class="active"><a data-toggle="tab" href="#category_component">Lihat semua</a></li>
					    	<li><a data-toggle="tab" href="#search_cat_component">Cari</a></li>
					  	</ul>

					  	<div class="tab-content">
					  		<div id="category_component" class="tab-pane fade in active">
				        		<ul>
				        			{!! $list_cat !!}
				        		</ul>
				        		<button style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control add_menu">Add to Menu</button>
			        		</div>

			        		<div id="search_cat_component" class="tab-pane fade">
    							<input type="text" name="search_component" class="form-control">
    							<div class="search-result">
    								<ul>
    								</ul>
				        			<button style="margin-top: 10px;display: none;" class="btn btn-round btn-fill btn-info form-control add_menu">Add to Menu</button>
    							</div>
    						</div>
			        	</div>
		        	</div>
		      	</div>
		    </div>
		    @endif

		    <div id="menu_url" class="panel panel-default">
		      	<div class="panel-heading">
		        	<h4 class="panel-title">
		          	Custom Link <a data-toggle="collapse" data-parent="#menu_component" href="#collapse4"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		        	</h4>
		      	</div>
		      	<div id="collapse4" class="panel-collapse collapse">
		        	<div class="panel-body form-group">
		        		<label>Label</label>
		        		<input id="custom-label" class="form-control" type="text" name="title">
		        		<label>URL</label>
		        		<input id="custom-url" class="form-control" type="url" name="url" value="http://">
		        		<button style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control add_menu">Add to Menu</button>
			      	</div>
		      	</div>
		    </div>
		</div>
		<div class="col-md-9">

			<!-- <form id="select-menu">
			<div class="form-inline">
				<label>Select menu to edit: </label>
				<select class="form-control" id="select-menu-option">
					<option value="top-menu">Top Menu</option>
					<option value="footer-menu">Footer Menu</option>
				</select>
				<button type="button" class="btn btn-default" onclick="select_menu()">Select</button>
			</div>
			</form> -->

			<div class="col-md-12 panel panel-default menu-set-wrap">
				<h3>Menu Structure <button class="save-menu btn btn-round btn-fill btn-success pull-right" menu-type="top-menu">Save</button></h3>
				<span id="saved" style="float: right;display: none;">Saved</span>
				<div class="dd panel-group" id="menu-structure">
				    <ol class="dd-list">
				    	
				    </ol>
				    <button class="save-menu btn btn-round btn-fill btn-success pull-left" style="margin-top: 15px;" menu-type="top-menu">Save</button>
				</div>
			</div>
		</div>
	</div>
	
</div>
@stop
