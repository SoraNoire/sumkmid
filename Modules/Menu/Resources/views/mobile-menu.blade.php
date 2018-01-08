@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	@if(session('msg'))
	<div class="alert alert-{{ session('status') }}">
	  {{ session('msg') }}
	</div>
	@endif
    <h4 class="title">Menu Mobile</h4>
    <p class="category">Edit posisi menu untuk mobile</p>
	    
	<div class="row">
		<div class="col-md-3 panel-group" id="menu_component">
		    <div id="menu_category_mobile" class="panel panel-default">
		      	<div class="panel-heading">
		        	<h4 class="panel-title">
		          	Category <a data-toggle="collapse" data-parent="#menu_component" href="#collapse3"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a>
		        	</h4>
		      	</div>
		      	<div id="collapse3" class="panel-collapse collapse in">
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
		</div>
		<div class="col-md-9 panel panel-default menu-set-wrap">
			<h3>Menu Structure <button class="save-menu-mobile btn btn-round btn-fill btn-success pull-right">Save</button></h3>
			<span id="saved" style="float: right;display: none;">Saved</span>
			<div class="dd panel-group" id="menu-structure">
			    <ol class="dd-list">
			        <?php echo $menu_structure ?>
			    </ol>
			    <button class="save-menu-mobile btn btn-round btn-fill btn-success pull-left" style="margin-top: 15px;">Save</button>
			</div>
		</div>
	</div>
	
</div>
@stop
