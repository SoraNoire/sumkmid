@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Video</h2>
	</div>
</div>

<div id="video-archive">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<h3>Hasil Pencarian Video dari "{{ $var['query'] }}"</h3>
			</div>
			<div class="searchVideo col-4" style="margin-bottom: 40px;float: right;">
				<div class="searchBox">
					<div class="inputWrap">
						<form action="{{ route('search_video','') }}" method="get">
							<input placeholder="Kata Kunci" type="text" name="q" value="{{$var['query']}}">
							<button><span class="icon i-search"></span></button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="infinite-scroll">
			@if(count($var['videos']) > 0 )
				@foreach($var['videos'] as $video)
				<div class="cst-3-col">
					<div class="item" style="background-image: url('{{$video->featured_image}}');">
						<a href="{{route('public_video').'/'.$video->slug}}"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a>
						<div class="item-title">
							<a href="{{route('public_video').'/'.$video->slug}}">{{ $video->title }}</a>
						</div>		
					</div>
				</div>
				@endforeach
			@else
				<p class="resultSearchHeading">Hasil pencarian dari "{{$var['query']}}" tidak ditemukan</p>
			@endif
			{{ $var['videos']->links() }}
			</div>
			<div class="atEnd">
				<span class="end-text">Tidak Ada Video Lagi</span>
			</div>
		</div>
	</div>
</div>
@endsection