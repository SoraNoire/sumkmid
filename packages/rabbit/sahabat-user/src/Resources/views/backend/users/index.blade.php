@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Module Management</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert alert-success">
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
                                @if( in_array($user->role,['visitor','Visitor']))
                                <a href="{{route('panel.user__edit__detail',$user->id)}}" class="btn btn-primary">Edit</a>
                                @endif
                                @if('admin'!=$user->role)
                                <a href="{{route('panel.user__delete',$user->id)}}" class="btn btn-danger">Delete</a>
                                @endif
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

        <div class="row">
            <div class="col-sm-5">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                    Showing {{(0==$page->userstart)?1:$page->userstart}} to {{($page->userstart+$page->perpage > $page->usersum)? $page->usersum : $page->userstart+$page->perpage}} of {{$page->usersum}} entries
                </div>
            </div>
            <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    <ul class="pagination">
                        <li class="paginate_button previous" id="example2_previous"><a href="{{ isset($search_query) ? '?page=1&search='.$search_query : '?page=1' }}" aria-controls="example2" data-dt-idx="0" tabindex="0"><<</a></li>

                        @for($i=$page->start;$i<=$page->end;$i++)
                            
                            @if($i==$page->current)
                                <li class="paginate_button active">
                                    <a href="{{ isset($search_query) ? '?page='.$i.'&search='.$search_query : '?page='.$i }}" aria-controls="example2" data-dt-idx="1" tabindex="0">{{$i}}</a></li>
                            @else
                                <li class="paginate_button"><a href="{{ isset($search_query) ? '?page='.$i.'&search='.$search_query : '?page='.$i }}" aria-controls="example2" aria-controls="example2" data-dt-idx="1" tabindex="0">{{$i}}</a></li>
                            @endif
                        
                        @endfor

                        <li class="paginate_button next" id="example2_next"><a href="{{ isset($search_query) ? '?page='.$page->sum.'&search='.$search_query : '?page='.$page->sum }}" aria-controls="example2" data-dt-idx="7" tabindex="0">>></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <a class="btn btn-success" href="{{route('panel.user__view__export')}}">Export Data Pengguna</a>
        </div>


	</div>
</div>

@endsection


