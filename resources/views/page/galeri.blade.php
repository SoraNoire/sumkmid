@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Galeri</h2>
	</div>
</div>

<div id="video-archive">
	<div class="container">
	<div class="row">
	@if (isset( $var['archive'] ))
		<h3 style="text-transform: capitalize;">{{ $var['archive'] }}</h3>
	@endif
	<div class="infinite-scroll">
	@foreach($var['items'] as $content)
		@if($content->post_type == 'gallery')
		<div class="cst-3-col thumbnailGallery">
			<div class="item" style="background-image: url('{{$content->featured_image}}');">
				<a href="{{route('public_galeri').'/'.$content->slug}}"><span class="play-button"><i class="fa fa-picture-o" aria-hidden="true"></i></span></a>
				<div class="item-title">
					<a href="{{route('public_galeri').'/'.$content->slug}}">{{ $content->title }}</a>
				</div>		
			</div>
		</div>
		@else
		<div class="cst-3-col">
			<div class="item" style="background-image: url('{{$content->featured_image}}');">
				<a href="{{route('public_galeri').'/'.$content->slug}}"><span class="play-button">
					<i class="fa fa-play fa-lg" aria-hidden="true"></i>
				</span></a>
				<div class="item-title">
					<a href="{{route('public_galeri').'/'.$content->slug}}">{{ $content->title }}</a>
				</div>		
			</div>
		</div>
		@endif

	@endforeach
	{{ $var['items']->links() }}
	</div>
	<div class="atEnd">
		<span class="end-text">Tidak Ada Video Lagi</span>
	</div>
	</div>
</div>
@endsection