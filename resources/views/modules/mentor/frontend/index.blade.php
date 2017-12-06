@extends('auth::layouts.app')

@section('title', '| Mentors')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-1">
	    @foreach ($mentors as $mentor)
	    	<div class="col-sm-3 col-xs-12">
	    		<img src="{{$mentor->foto_profil ?? '#'}}" class="img img-responsive"><br>
	    		<span> Name : {{$mentor->name}}</span><br>
	    		<span> {{json_decode($mentor->description)->mentor ?? ''}}</span>
	    	</div>
	    @endforeach
	</div>
</div>

@endsection