@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Mentor</h2>
	</div>
</div>

<div id="mentor-archive">
	<div class="container">
		<div class="row">
		@foreach($var['mentors'] as $mentor)
		<div class="cst-4-col">
		<div class="item">
			<div class="item-image" style="background-image: url('{{$mentor->foto_profil}}');"></div>
        	<div class="item-title">
        		<a href="#" title="Dito Arifin">
        			{!! str_limit(html_entity_decode(strip_tags($mentor->name)), 40) !!}
        		</a>
        		<a href="#" class="sub" title="CEO Anak Mas">{{ $mentor->jabatan }}</a>
        	</div>		
		</div>
		</div>
		@endforeach
		</div>
	</div>
</div>
@endsection