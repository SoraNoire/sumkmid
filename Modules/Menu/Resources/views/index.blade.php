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
			<div class="panel panel-default">
		      	<div class="panel-heading">
			        <h4 class="panel-title">
			          Page <a data-toggle="collapse" data-parent="#menu_component" href="#collapse1"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
			        </h4>
		      	</div>
		      	<div id="collapse1" class="panel-collapse collapse in">
		        	<div class="panel-body form-group">
		        		<ul>
		        		@if (count($pages) > 0)
		        		@foreach($pages as $page)
		        		<li>
		        		<label><input type="checkbox" name="menu_page" value="{{ $page->id }}" data-link="{{ $page->slug }}" data-label="{{ $page->title }}"> {{ $page->title }}</label></li>
		        		@endforeach
		        		@else
		        		<span>No Page Found</span>
		        		@endif
		        		</ul>
		        		<button id="add_page_menu" style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control">Add to Menu</button>
		        	</div>
		      	</div>
		    </div>
		    <div class="panel panel-default">
		      	<div class="panel-heading">
			        <h4 class="panel-title">
			          Post <a data-toggle="collapse" data-parent="#menu_component" href="#collapse2"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
			        </h4>
		      	</div>
		      	<div id="collapse2" class="panel-collapse collapse">
		        	<div class="panel-body form-group">
		        		<ul>
		        		@if (count($posts) > 0)
		        		@foreach($posts as $post)
		        		<li>
		        		<label><input type="checkbox" name="menu_post" value="{{ $post->id }}" data-link="{{ $post->slug }}" data-label="{{ $post->title }}"> {{ $post->title }}</label></li>
		        		@endforeach
		        		@else
		        		<span>No Post Found</span>
		        		@endif
		        		</ul>
		        		<button id="add_post_menu" style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control">Add to Menu</button>
		        	</div>
		      	</div>
		    </div>
		    <div class="panel panel-default">
		      	<div class="panel-heading">
		        	<h4 class="panel-title">
		          	Category <a data-toggle="collapse" data-parent="#menu_component" href="#collapse3"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		        	</h4>
		      	</div>
		      	<div id="collapse3" class="panel-collapse collapse">
		        	<div class="panel-body">
		        		<ul>
		        		@if (count($category) > 0)
		        		@foreach($category as $cat)
		        		<li><label><input type="checkbox" name="menu_category" value="{{ $cat->id }}" data-link="{{ $cat->slug }}" data-label="{{ $cat->name }}"> {{ $cat->name }}</label></li>
		        		@endforeach
		        		@else
		        		<span>No Category Found</span>
		        		@endif
		        		</ul>
		        		<button id="add_category_menu" style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control">Add to Menu</button>
		        	</div>
		      	</div>
		    </div>
		    <div class="panel panel-default">
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
		        		<button id="add_link_menu"  style="margin-top: 10px;" class="btn btn-round btn-fill btn-info form-control">Add to Menu</button>
			      	</div>
		      	</div>
		    </div>
		</div>
		<div class="col-md-9 panel panel-default menu-set-wrap">
			<h3>Menu Structure <button id="save-menu" class="btn btn-round btn-fill btn-success pull-right">Save</button></h3>
			<span id="saved" style="float: right;display: none;">Saved</span>
			<div class="dd panel-group" id="menu-structure">
			    <ol class="dd-list">
			        <?php echo $menu_structure ?>
			    </ol>
			</div>
		</div>
	</div>
	
</div>
@stop
