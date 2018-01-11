@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Edit User</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert">
	    			{!! session('message') !!}
	    		</div>
	    	@endif
	        <form method="post" action="{{ route('panel.user__update',$user->id) }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                	<div class="col-md-6 col-sm-12">
							<div class="form-group label-floating">
								<label class="control-label">Nama Lengkap</label>
								<input type="hidden" name="id" value="{{$user->id}}">
								<input type="text" disabled class="form-control" name="name" value="{{$user->name??''}}" />
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="text" disabled class="form-control" name="email" value="{{$user->email??''}}" />
							</div>
							<!-- <button type="submit" class="btn btn-success pull-left">Update</button> -->
							
							@if(isset($user->data->type_user) && in_array($user->data->type_user,['perorangan','umkm']) || $user->role == 'Visitor')
							<a style="margin-left: 5px;" class="btn btn-primary" href="{{route('panel.user__edit__detail',$user->id)}}">Selengkapnya</a>
							@endif
						</div>  
	            </div>
	            <div class="clearfix"></div>  
	        </form>
	    </div>
	</div>

</div>
<div class="col-sm-12">
	
</div>


@endsection


