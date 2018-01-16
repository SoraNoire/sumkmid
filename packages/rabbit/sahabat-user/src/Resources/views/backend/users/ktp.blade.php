@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Cek KTP</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert alert-success">
	    			{!! session('message') !!}
	    		</div>
	    	@endif
	        
            <div class="row">
                @foreach($users as $user)
                    @php
                        $path = storage_path('cr/ktp/'.$user->foto_ktp);
                        $ktp = file_get_contents($path);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <div class="col-sm-12 col-md-6">
                        <div class="thumbnail text-center">
                            <img src="{{$base64}}" alt="" class="img-responsive" style="width: 100%;max-height: 250px;">
                            <a href="{{route('panel.user__edit__detail',$user->id)}}">
                                <div class="caption">
                                    {{$user->username?? ''}}
                                </div>
                            </a>
                        </div>
                        
                    </div>
                    @php unset($base64);unset($ktp); @endphp
                @endforeach
            </div>
        

	    </div>

        <div>
            <div class="col-sm-12 col-md-5" style="padding: 20px 0 0 0;">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                    Showing {{(0==$page->userstart)?1:$page->userstart}} to {{($page->userstart+$page->perpage > $page->usersum)? $page->usersum : $page->userstart+$page->perpage}} of {{$page->usersum}} entries
                </div>
            </div>
            <div class="col-sm-12 col-md-7">
                <div class="dataTables_paginate " id="example2_paginate">
                    <ol class="pagination" style="display: block;">
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
                    </ol>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>


	</div>
</div>
<style type="text/css">
    .thumbnail {
        position: relative;
    }

    .caption {
        -webkit-transition: color .7s linear;
        -moz-transition: color .7s linear;
        -o-transition: color .7s linear;
        -ms-transition: color .7s linear;
        transition: color .7s linear;
        -webkit-transition: background-color .7s linear;
        -moz-transition: background-color .7s linear;
        -o-transition: background-color .7s linear;
        -ms-transition: background-color .7s linear;
        transition: background-color .7s linear;
        position: absolute;
        padding: 5% 0!important;
        width: 98%!important;
        left: 1%;
        bottom: 1%;
        width: 100%;
        background-color: rgba(0,0,0,0.5);
        color: #FFF!important;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
    }
    .caption:hover {
        color: #000!important;
        background-color: rgba(255,255,255,0.7);
    }
</style>
@endsection


