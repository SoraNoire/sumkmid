@extends('oa::layouts.base')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Role Management</h3>
	    </div>
	    <div class="card-content">
	        <form method="post" action="{{ url('administrator/save-app-setting') }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                <div class="col-md-12">
	                	@foreach($roles as $role)
						<div class="form-group label-floating">
							<label class="control-label">{{$role}}</label>
							
							@foreach ($modules as $module)
								{{$module}}
							@endforeach

						</div>
						@endforeach
	                </div>
	            </div>
	            <button type="submit" class="btn btn-success pull-left">Simpan</button>
	            <div class="clearfix"></div>
	        </form>
	    </div>
	</div>
</div>

@endsection


