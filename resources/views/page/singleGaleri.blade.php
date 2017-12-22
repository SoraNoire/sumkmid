@extends('layouts.publicbase')

@section('content')
@if($var['content']->post_type == 'gallery')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">
<div class="breadcrumb">
	<div class="container">
		<h2>{{ $var['content']->post_type == 'video' ? 'Video' : 'Foto' }}</h2>
	</div>
</div>

<section class="singleVideo">
	<div class="videoHeading">
		<div class="container">
			<h3>{{$var['content']->title}}</h3>
			<div class="postMeta">

				@if(count($var['categories']) > 0)
				<div class="postCategory">
					<span class="icon i-paper"></span>
					<span>
						@foreach($var['categories'] as $category)
						<a href="{{ route('video_cat_archive', $category->slug) }}">{{ $category->name }}</a>
						@endforeach
					</span>
				</div>
				@endif
				@if(count($var['tags']) > 0)
				<div class="postTag">
					<span class="icon i-tag"></span>
					<span>
						@foreach($var['tags'] as $tag)
						<a href="{{ route('video_tag_archive', $tag->slug) }}">{{ $tag->name }}</a>
						@endforeach
					</span>
				</div>
				@endif
			</div>
		</div>
	</div>
	<div class="videoContainer">
		<div class="container">
			@if($var['content']->post_type == 'video')
			<iframe src="{{$var['videoEmbed']}}" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
			@else
			<!-- Slider main container -->
			<!-- Swiper -->
			<div class="swiper-container gallery-top">
				<div class="swiper-wrapper">
					<div class="swiper-slide" style="background-image:url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0css04274.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/broccoli.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/tradingbusinessplan.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0c04274.jpg') }})"></div>
				</div>
				<!-- Add Arrows -->
				<div class="swiper-button-next swiper-button-white"></div>
				<div class="swiper-button-prev swiper-button-white"></div>
			</div>
			<div class="swiper-container gallery-thumbs">
				<div class="swiper-wrapper">
					<div class="swiper-slide" style="background-image:url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0css04274.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/broccoli.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/tradingbusinessplan.jpg') }})"></div>
					<div class="swiper-slide" style="background-image:url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0c04274.jpg') }})"></div>
				</div>
			</div>
			@endif
		</div>
	</div>
	<div class="videoDescription">
		<div class="container">
			<div class="row">
				<div class="col-9">
					<div class="wrapDescription styledText">
						{!! $var['content']->content !!}
					</div>
					<div class="attachmentVideo">
						<h4>Unduh Dokumen Pendukung :</h4>
						<ul>
							<li>
								<a href="#">
									<span class="icon i-download"></span>
									<span>Action sheet 1</span>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="icon i-download"></span>
									<span>Action sheet 1</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="videoNavigation {{ ($var['prevItem'] == '' ? 'singleNavNext' : '') }} {{ ($var['nextItem'] == '' ? 'singleNavPrev' : '') }} ">
						<div class="prevVideo">
							<a href="{{route('public_galeri').'/'.$var['prevItem']}}">
								<span class="icon i-prev"></span>
								<span>Galeri sebelumnya</span>
							</a>
						</div>
						<div class="nextVideo ">
							<a href="{{route('public_galeri').'/'.$var['nextItem']}}">
								<span>Galeri selanjutnya</span>
								<span class="icon i-next"></span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="sidebarVideo">
						@if (count($var['allcategories']) > 0)
						<div class="sidebar kategoriWidget">
							<h4 class="sidebarDefaultHeading">Kategori</h4>
							<ul>
								@foreach($var['allcategories'] as $cat)
								<li><a href="{{ route('video_cat_archive', $cat->slug) }}">{{ $cat->name }}</a></li>
								@endforeach
							</ul>
						</div>
						@endif
						<div class="sidebar searchVideo">
							<h4 class="sidebarDefaultHeading">Cari di Galeri</h4>
							<div class="searchBox">
								<div class="inputWrap">
									<form action="{{ route('search_galeri','') }}" method="get">
										<input placeholder="Kata Kunci" type="text" name="q" id="">
										<button><span class="icon i-search"></span></button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@if($var['content']->post_type == 'gallery')
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>
<script>
    var galleryTop = new Swiper('.gallery-top', {
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
    var galleryThumbs = new Swiper('.gallery-thumbs', {
      spaceBetween: 10,
      centeredSlides: true,
      slidesPerView: 'auto',
      touchRatio: 0.2,
      slideToClickedSlide: true,
    });
    galleryTop.controller.control = galleryThumbs;
    galleryThumbs.controller.control = galleryTop;
  </script>

@endsection