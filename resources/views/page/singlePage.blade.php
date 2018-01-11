@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>{{ $var['page'] }}</h2>
	</div>
</div>

<div id="singlePage">
	<div class="container">
		<div class="row">
			<div class="col-12 pageContent">
				{!! $var['content'] !!}
			</div>
		</div>
	</div>
</div>
@endsection