@extends('layouts.publicbase')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">
<section id="mainSlider">
<!-- Slider main container -->
<div class="swiper-container main-slider">
    <div class="swiper-wrapper">

    	@foreach( $var['sliders'] as $slider )
        <div class="swiper-slide bigSlideContent" style="background-image: url({{ $slider->image ?? '' }});">
        	<div class="contentWrap {{ $slider->position == 'right' ? 'onRight' : 'onLeft' }}">
        		<div class="content">
        			<div class="heading">
        				{!! $slider->title ?? '' !!}
        			</div>
        			<div class="caption">
        				{!! $slider->description ?? '' !!}
        			</div>
        			@if ( $slider->btn_text != '' )
	        		<a href="{{ $slider->link }}" class="button">{{ $slider->btn_text }}</a>
	        		@endif
        		</div>
        	</div>
        </div>
        @endforeach

    </div>
    <div class="swiper-pagination"></div>
</div>
</section>

<section id="about-us">
	<div class="container">
		<h3 class="section-title gray"><span>Tentang</span> <span>Kami</span></h3>
		<div class="section-desc">
			<p>
				{{ $var['about_us'] ?? '' }}
			</p>
		</div>
	</div>
</section>

<section id="quote" class="blue-bg">
    <div class="container">
        <div class="the-row">
        	@if ( isset($var['quote']->image) && $var['quote']->image != '' )
        	<div class="col-3">
	        	<div class="quotePhotoWraper">
		            <div class="quotePhoto">
		                <img src="{{ $var['quote']->image ?? '' }}">
		            	}
		            </div>
	            </div>
            </div>
            @endif
            <div class="col-9">
	            <div class="quote">
	                <blockquote>
	                    "{{ $var['quote']->text ?? '' }}"
	                </blockquote>
	            </div>
	            <div class="quoter">
	        		<p>{{ $var['quote']->from ?? '' }}</p>
	        	</div>
           	</div>
        </div>
    </div>
</section>

<section id="programs">
	<div class="programWrap">
		@foreach( $var['programs'] as $program )
		<div class="programSplit" style="background-image: url({{ $program->background }})">
			<div class="logo">
				<img src="{{ $program->logo }}">
			</div>
			<div class="heading">
				<a href="{{ $program->url ?? '#' }}"><span>{{ $program->title }}</span></a>
			</div>
			<div class="caption">
				<p>{{ $program->description }}</p>
			</div>
		</div>
		@endforeach

	</div>
</section>

<section id="main-gallery">
	<div class="container">
		<h3 class="section-title">{!! $var['gallery_name'] !!}</h3>
		<div class="swiper-container gallery-slider">
			<div class="swiper-wrapper">
				@foreach ($var['videos'] as $video)
				<div class="swiper-slide">
					<div class="g-mini-frame">
						<div class="thumbnail-wraper" style="background-image: url('{{ $video->featured_image ?? '' }}');">
		        		<!-- <a href="{{route('single_gallery', $video->slug ?? '')}}"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a> -->
		        		</div>
		        		<div class="meta">
							<a title="{{ $video->title ?? '' }}" href="{{route('single_gallery', $video->slug ?? '')}}" class="title">{{ $video->title ?? '' }}</a>
							<small class="category">
							@foreach(PostHelper::get_post_category($video->id) as $category)
								{{ $category->name }}, 
							@endforeach
							</small>
							<span class="desc">
								@if(PostHelper::get_post_meta($video->id))
									{{PostHelper::get_post_meta($video->id)['meta_desc']}}
								@endif
							</span>
							<span class="date"><i class="fa fa-calendar" aria-hidden="true"></i>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($video->published_date))->diffForHumans() }}</span>
							<a title="{{ $video->title ?? '' }}" href="{{route('single_gallery', $video->slug ?? '')}}" class="readmore">READMORE</a>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="galleryGoLeft">
			<img src="{{ asset('images/left.png') }}">
		</div>
		<div class="galleryGoRight">
			<img src="{{ asset('images/right.png') }}">
		</div>
<!-- 		<div class="the-row">
			@foreach ($var['videos'] as $video)
			<div class="col-3">
				<div class="video-wraper" style="background-image: url('{{ $video->featured_image ?? '' }}');">
	        		<a href="{{route('single_gallery', $video->slug ?? '')}}"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a>
	        	</div>
				<a href="{{route('single_gallery', $video->slug ?? '')}}" class="tilte">{{ $video->title ?? '' }}</a>
			</div>
			@endforeach
		</div> -->
	</div>
</section>

@if (isset($var['instagram']))
<section id="instagram-feed" class="blue-bg">
	<div class="container">
		<h3 class="section-title"><span>Social</span> <span>Feeds</span></h3>
		<div class="swiper-container insta-slider">
			<div class="swiper-wrapper">
				@php $i = 0 @endphp
				@foreach($var['instagram'] as $feed)
				@php $i++ @endphp
				@if($i == 10)
					@php break @endphp
				@endif
				<div class="swiper-slide">
					<a href="{{ $feed->link ?? '' }}">
					<div class="post-wraper" style="background-image: url('{{ $feed->images->standard_resolution->url ?? '' }}');">
		        	</div>
		        	</a>
				</div>
				@endforeach
			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination insta-pagination"></div>
		</div>

<!-- 		<div class="the-row">
		
			@php $i = 0 @endphp

			@foreach($var['instagram'] as $feed)
			@php $i++ @endphp
			@if($i == 5)
				@php break @endphp
			@endif
			<div class="col-3">
				<a href="{{ $feed->link ?? '' }}">
				<div class="post-wraper" style="background-image: url('{{ $feed->images->standard_resolution->url ?? '' }}');">
	        	</div>
	        	</a>
			</div>
			@endforeach
		</div> -->
	</div>
</section>
@endif

<section id="ourMentors">
	<div class="container">
		<h3 class="section-title grey"><span>Meet Our</span> <span>Mentor</span></h3>
			<div class="mentorWrap">
				@php $i = 0 @endphp

				@foreach($var['mentors'] as $mentor)
				@php $i++ @endphp
				@if($i == 5)
					@php break @endphp
				@endif
				<div id="mentors">
					<div class="photoMentor">
					    <div>
					        <img src="{{ $mentor->foto_profil ?? 'tidak ada' }}" alt=""/>
					    </div>
					</div>
					<h5 class="mentor-name">
						<a href="{{ route('public_mentor_single',$mentor->id ?? 0) }}">
							{{ $mentor->name ?? 'Anonim' }}
						</a>
					</h5>
					<span class="mentor-desc">{{ $mentor->jabatan ?? '' }}</span>
				</div>
				@endforeach
			</div>
	</div>
</section>

<div class="clearfix"></div>
<section id="mentor" style="display: none;">
	<div class="container">
		<h3 class="section-title gray"><span>Punya Masalah</span> <span>Bisnis?</span></h3>
		<div class="section-desc">
	    	<p>Kami punya lebih dari 100 mentor untuk menjawab semua<br> permasalahan bisnis UMKM anda</p>
		</div>
	</div>
</section>

<section id="questions" style="display: none;">
	<div class="fullWrap">
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
		<div class="question">
			<div class="caption">
				<p>Bagaimana cara promosi produk tanpa bayar iklan ?</p>
				<small>Ryan Purbo</small>
			</div>
		</div>
	</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>

@endsection
