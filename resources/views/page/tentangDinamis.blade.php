@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>{{ $var['data']->title }}</h2>
	</div>
</div>

<div id="tentang">
	<div class="container">
		<div class="row">
			<div class="col-12 tentangContent">
				{!! $var['data']->content !!}
			</div>
		</div>
	</div>
</div>
@endsection