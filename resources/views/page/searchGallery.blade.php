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
                <a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Video</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
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
						<form action="{{ route('search_gallery','') }}" method="get">
							<input placeholder="Kata Kunci" type="text" name="q" value="{{$var['query']}}">
							<button><span class="icon i-search"></span></button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="infinite-scroll">
			@if(count($var['posts']) > 0 )
				@foreach($var['posts'] as $post)
				<div class="cst-3-col">
					<div class="item" style="background-image: url('{{$post->featured_image}}');">
						<a href="{{url('/galeri/'.'/'.$post->slug)}}"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a>
						<div class="item-title">
							<a href="{{url('galeri/'.'/'.$post->slug)}}">{{ $post->title }}</a>
						</div>		
					</div>
				</div>
				@endforeach
			@else
				<p class="resultSearchHeading">Hasil pencarian dari "{{$var['query']}}" tidak ditemukan</p>
			@endif
			{{ $var['posts']->links() }}
			</div>
		</div>
	</div>
</div>
@endsection