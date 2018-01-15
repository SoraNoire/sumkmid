@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Tentang Kami</h2>
	</div>
</div>

<div id="tentangKami">
	<div class="container">
		{!! $var['content'] !!}
	</div>
</div>

@endsection