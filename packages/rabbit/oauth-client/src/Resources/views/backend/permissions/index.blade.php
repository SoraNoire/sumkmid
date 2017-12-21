@extends('oa::layouts.base')

@section('content')


<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Role Management</h3>
	    </div>
	    <div class="card-content">

	    	<div id="message" class="col-sm-12 hidden alert alert-success"></div>
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                <div class="col-md-12">


				    	<table id="role-tables" class="table table-responsive">
				    		
				    		<thead>
				    			<th>+</th>
				    			@foreach($roles as $role)
				    				<th>
				    					{{$role}}
				    				</th>
				    			@endforeach
				    		</thead>

				    		<tbody>
				    			@foreach ($modules as $key=>$module)
				    			
				    				<tr>
				    					<td> <span class="toggle">+</span></td>
				    					@foreach($roles as $role)
					    				<td>
					    					@php $perm = $rolepermissions[$role] ?? [] @endphp
					    					<!-- <input type="checkbox" name="$role."-".$module->id"> -->
											<b>{{ $module->readable_name }}</b>
					    					
										</td>
										@endforeach
									</tr>
									<tr>
										<td></td>
										@foreach($roles as $role)
										<td>
											@php $perms = $rolepermissions[$role][$module->id] ?? [] @endphp

											@foreach( app()->OAuth::$permissions as $permission )
												@if( isset($perms) )
														@if ($perms)
															@php $checked = (1==$perms->{$permission}) ? 'checked' : '' @endphp
															<br>
															<input type="checkbox" 
																name="check[{{$role.'-'.$module->id}}-{{$permission}}]" 
																{{$checked}}
																class="check-box" 
																value="{{$role.'-'.$module->id}}-{{$permission}}"
															/> {{ucfirst($permission)}}
														@else
															<br>
															<input type="checkbox" 
																name="check[{{$role.'-'.$module->id}}-{{$permission}}]" 
																class="check-box" 
																value="{{$role.'-'.$module->id}}-{{$permission}}"
															/> {{ucfirst($permission)}}
														@endif
												@endif

											@endforeach
											
										</td>
										@endforeach

									</tr>
								@endforeach
				    		</tbody>

				    		<tfoot>
				    			<th>+</th>
				    			@foreach($roles as $role)
				    				<th>
				    					{{$role}}
				    				</th>
				    			@endforeach
				    		</tfoot>
				    	</table>

	                </div>
	            </div>
	            
	            <div class="clearfix"></div>
	        
	    </div>
	</div>
</div>

<script type="text/javascript">
	sels = document.getElementsByClassName('check-box');
    for(i=0; i<sels.length; i++) {
        sels[i].addEventListener('change', function(i){
        	var checked = i.path[0].checked;
        	var value = i.path[0].value;
        	checked = (true==checked)?1:0;
        	saveJax(value,checked);
        }, false);
    }

    function saveJax(val='',check=false)
    {
    	if (''==val){
    		return false;
    	}
		var http = new XMLHttpRequest();
		var url = "{{route('OA.permissions.save.ajax')}}";
		var params = "val="+val+"&check="+check;
		http.open("POST", url, true);

		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		http.onreadystatechange = function() {//Call a function when the state changes.
		    if(http.readyState == 4 && http.status == 200) {
		    	var msg = document.getElementById("message");
		        msg.innerHTML = http.responseText;
		        notif.showNotification("top","right",http.responseText, 2);
		        console.log(http.responseText);
		    }
		}
		http.send(params);
	}

</script>

@endsection


