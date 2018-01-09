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
	        
            <table class="table table-responsive">
                
                <thead>
                    <th>Nama</th>
                    <th>Nama Pengguna</th>
                    <th>Type Pengguna</th>
                    <th>Aksi</th>
                </thead>

                <tbody>
                    @foreach($users as $user)

                        <tr>
                            <td>
                                {{$user->name}}
                            </td>
                            <td>
                                {{$user->username}}
                            </td>
                            <td>
                                {{$user->role}}
                            </td>
                            <td>
                                <a href="{{route('SHB.user__edit',$user->id)}}" class="btn btn-primary">Edit</a>
                                <a href="{{route('SHB.user__delete',$user->id)}}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>

                    @endforeach
                </tbody>

                <tfoot>
                    <th>Nama</th>
                    <th>Nama Pengguna</th>
                    <th>Type Pengguna</th>
                    <th>Aksi</th>
                </tfoot>

            </table>
        

	    </div>
	</div>
</div>

@endsection


