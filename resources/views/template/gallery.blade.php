@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Beranda</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ url('/galeri') }}"><span itemprop="name">Galeri</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="video-archive">
	<div class="container">
	<div class="row">
	@if (isset( $var['archive'] ))
		<h3 style="text-transform: capitalize;">{{ $var['archive'] }}</h3>
	@endif
	<div class="infinite-scroll">
	@foreach($var['posts'] as $content)
		@if($content->post_type == 'gallery')
		<div class="cst-3-col thumbnailGallery">
			<div class="item" style="background-image: url('{{$content->featured_image}}');">
				<a href="{{route('single_gallery', $content->slug)}}"><span class="play-button"><i class="fa fa-picture-o" aria-hidden="true"></i></span></a>
				<div class="item-title">
					<a href="{{route('single_gallery', $content->slug)}}">{{ $content->title }}</a>
				</div>		
			</div>
		</div>
		@else
		<div class="cst-3-col">
			<div class="item" style="background-image: url('{{$content->featured_image}}');">
				<a href="{{route('single_gallery', $content->slug)}}"><span class="play-button">
					<i class="fa fa-play fa-lg" aria-hidden="true"></i>
				</span></a>
				<div class="item-title">
					<a href="{{route('single_gallery', $content->slug)}}">{{ $content->title }}</a>
				</div>		
			</div>
		</div>
		@endif

	@endforeach
	{{ $var['posts']->links() }}
	</div>
	</div>
</div>
@endsection