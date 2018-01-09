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
		<div class="row">
			<div class="col-7">
				<h3 class="section-title"><span>Tentang</span> <span>Kami</span></h3>
				<div class="section-desc">
					<p>
						{{ $var['about_us'] ?? '' }}
					</p>
				</div>	
			</div>
			<div class="col-5">
				<div class="sahabaticon">
					<a href="{{ URL::to('/') }}">
						<img src="{{ asset('images/sbt-icon.png') }}">
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="quote">
    <div class="container">
    	<h3 class="section-title">{!! $var['program']->title ?? '<span>Our</span><span>Program</span>' !!}</h3>
        <div class="the-row">
        	<!-- @if ( isset($var['quote']->image) && $var['quote']->image != '' )
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
           	</div> -->
           	<div class="col-12">
           		<p class="why-shld-join">
           			{{ $var['program']->desc ?? '' }}
           		</p>
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
	<div class="registerNow">
		<a href="{{ $var['program']->url ?? '#' }}">
			<span class="button">{{ $var['program']->button ?? '' }}</span>
		</a>
	</div>
</section>

<section id="main-gallery">
	<div class="container">
		<h3 class="section-title">{!! $var['post']->title ?? '<span>Galeri Sahabat</span> <span>UMKM</span>' !!}</h3>
		<div class="swiper-container gallery-slider">
			<div class="swiper-wrapper">
				@foreach ($var['post']->data as $post)
				<div class="swiper-slide">
					<div class="g-mini-frame">
						<div class="thumbnail-wraper" style="background-image: url('{{ $post->featured_img ?? '' }}');">
		        		</div>
		        		<div class="meta">
							<a title="{{ $post->title ?? '' }}" href="{{ $post->link }}" class="title">{{ $post->title ?? '' }}</a>
							<span class="desc">
								@if( isset($post->post_desc) )
									{{ $post->post_desc }}
								@endif
							</span>
							<span class="date"><i class="fa fa-calendar" aria-hidden="true"></i>{{ \Carbon\Carbon::createFromTimeStamp(strtotime( $post->date_published ))->toFormattedDateString() }}</span>
							<a title="{{ $post->title ?? '' }}" href="{{ $post->link }}" class="readmore">READ MORE</a>
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
