@extends('oa::layouts.base')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Module Management</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert">
	    			{!! session('message') !!}
	    		</div>
	    	@endif
	        

	        <form method="post" action="{{ route('OA.module.save') }}" accept-charset="UTF-8">
            	<table class="table table-responsive">
            		
            		<thead>
            			<th>
            				Module Name
            			</th>

            			<th>
            				Module Cname
            			</th>

            			<th>
            				Action
            			</th>
            			
            		</thead>

            		<tbody>
            			@foreach($modules as $key=>$module)
            				@if ( isset($module->id) )
            					<tr class="bg-success">
            				@else
            					<tr class="bg-warning">
            				@endif
                			
                				<td>
                					<input type="hidden" name="_token" value="{{ csrf_token() }}">
                					{{substr($module->name,6)}}
                				</td>
                				<td>
                					<input type="text" name="modulecname[{{$module->name}}]" value="{{$module->readable_name}}">
                				</td>

                				<td>
                					@if ( isset($module->id) )
		            					<input checked type="checkbox" name="check[{{$module->name}}]" value="{{$module->name}}"> Use <br>
		            					<input type="checkbox" name="delete[]" value="{{$module->id}}"> Delete
		            				@else
		            					<input type="checkbox" name="check[{{$module->name}}]" value="{{$module->name}}"> Use
		            				@endif
                				</td>
                			</tr>	
            			@endforeach
            		</tbody>

            		<tfoot>
            			<th>
            				Module Name
            			</th>

            			<th>
            				Module Cname
            			</th>
            			
            			<th>
            				Action
            			</th>

            		</tfoot>

            	</table>
            	<button type="submit" class="btn btn-success pull-left">Simpan</button>
	            <div class="clearfix"></div>
	        </form>
        

	    </div>
	</div>
</div>

@endsection


