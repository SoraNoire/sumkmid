@extends('oa::layouts.base')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Edit Module</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert">
	    			{!! session('message') !!}
	    		</div>
	    	@endif
	        <form method="post" action="{{ route('OA.module.update') }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                	
	                	<div class="col-md-6 col-sm-12">
							<div class="form-group label-floating">
								<label class="control-label">Nama Module</label>
								<input type="hidden" name="id" value="{{$module->id}}">
								<input type="text" class="form-control" name="name" value="{{$module->name??''}}" />
							</div>
						</div>
	                
	            </div>
	            <button type="submit" class="btn btn-success pull-left">Update</button>
	            <div class="clearfix"></div>
	        </form>
	    </div>
	</div>
</div>

@endsection


