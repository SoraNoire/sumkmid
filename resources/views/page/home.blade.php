@extends('layouts.publicbase')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">
<section id="mainSlider">
<!-- Slider main container -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide bigSlideContent" style="background-image: url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0c04274.jpg') }});">
        	<div class="contentWrap onLeft">
        		<div class="content">
        			<div class="heading">Mentoring Online Pelaku UMKM</div>
        			<div class="caption">Bergabung bersama ratusan pelaku UMKM dan dapatkan manfaat dari bimbingan bisnis langsung dari gadget anda.</div>
        		</div>
        	</div>
        </div>
        <div class="swiper-slide bigSlideContent" style="background-image: url({{ asset('images/e2f87ecce1c734ee1b2e8cb8d0c04274.jpg') }});">
        	<div class="contentWrap onRight">
        		<div class="content">
        			<div class="heading">Mentoring Online Pelaku UMKM</div>
        			<div class="caption">Bergabung bersama ratusan pelaku UMKM dan dapatkan manfaat dari bimbingan bisnis langsung dari gadget anda.</div>
        		</div>
        	</div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>
</section>

<section id="what-we-do">
	<div class="bg-overlay"></div>
	<div class="container">
	    <div class="row">
	        <div class="col-5 left-content">
	        	<h3 class="section-title"><span>What</span> <span>We Do</span></h3>
	        	<div class="section-desc">
		        	<p>
		        		Appropriately communicate pandemic initiatives through intuitive testing procedures. Appropriately distinctive paradigms after enabled cloud services. Globally productize sustainable sources with corporate functionalities. Interactively facilitate virtual quality vectors before adaptive e-commerce.
		        	</p>
	        	</div>
	        	<a href="http://authdev.mdirect.id/register" class="button">Daftar</a>
	        </div>
	        <div class="col-7 right-content">
	        	<div class="video-wraper" style="background-image: url('/img/top-video-bg.png');">
	        		<a href="/video/what-we-do">
	        			<img src="/img/play-button.png">
	        		</a>
	        	</div>
	        </div>
	    </div>
    </div>
</section>

<section id="event">
	<div class="container">
	    <div class="row">
	        <div class="col-5 left-content">
	        	<h3 class="section-title gray"><span>Event</span> <span>UMKM</span></h3>
	        	<div class="section-desc">
		        	<p>
		        		Completely communicate granular processes whereas ethical ideas. Dynamically streamline high-payoff methodologies and resource-leveling process improvements. Collaboratively create wireless opportunities via high-quality convergence. 
		        	</p>
	        	</div>
	        	<a href="{{ route('public_event') }}" class="button blue-shadow">Lihat Event</a>
	        </div>
	        <div class="col-7 right-content">
	        	<div class="block-wraper">
	        		<div class="block"></div>
	        		<div class="block quote">
	        			<span class="quote-logo"><i class="material-icons">format_quote</i></span>
	        			<div class="the-quote">
		        			<p>Event-nya seru semua, ajang yang tepat buat cari partner dan peluang baru</p>
		        			<span>- umar</span>
	        			</div>
	        		</div>
	        		<div class="block"></div>
	        	</div>
	        </div>
	    </div>
    </div>
</section>

<section id="programs">
	<div class="programWrap">
		<div class="programSplit" style="background-image: url({{asset('images/program/1.jpg')}})">
			<div class="logo">
				<img src="{{ asset('images/niexjjzstcseuzdzkvoq.png') }}">
			</div>
			<div class="heading">
				<span>Mentoring</span>
			</div>
			<div class="caption">
				<p>Mempertemukan Pelaku UMKM dengan Pelaku Profesional yang	dapat membantu untuk meningkatkan dan mengembangkan potensi usaha pelakunya</p>
			</div>
		</div>
		<div class="programSplit" style="background-image: url({{asset('images/program/2.jpg')}})">
			<div class="logo">
				<img src="{{ asset('images/niexjjzstcseuzdzkvoq.png') }}">
			</div>
			<div class="heading">
				<span>Forum</span>
			</div>
			<div class="caption">
				<p>Sarana bagi para	pelaku UMKM	Indonesia untuk saling memberikan kontribusi, bertukar informasi,	menyampaikan ide, ilmu, dan pengalaman dalam mengembangkan usahanya</p>
			</div>
		</div>
		<div class="programSplit" style="background-image: url({{asset('images/program/3.jpg')}})">
			<div class="logo">
				<img src="{{ asset('images/niexjjzstcseuzdzkvoq.png') }}">
			</div>
			<div class="heading">
				<span>Legal</span>
			</div>
			<div class="caption">
				<p>Mempertemukan Pelaku UMKM dengan Pelaku Profesional yang	dapat membantu untuk meningkatkan dan mengembangkan potensi usaha pelakunya</p>
			</div>
		</div>
		<div class="programSplit" style="background-image: url({{asset('images/program/4.jpg')}})">
			<div class="logo">
				<img src="{{ asset('images/niexjjzstcseuzdzkvoq.png') }}">
			</div>
			<div class="heading">
				<span>Event</span>
			</div>
			<div class="caption">
				<p>Bergabung dan bertemu dengan sesama pelaku UMKM melalui kegiatan-kegiatan off-air Sahabat UMKM</p>
			</div>
		</div>
	</div>
</section>

<section id="ourMentors">
	<div class="container">
		<h3 class="section-title"><span>Our</span> <span>Mentor</span></h3>
		<div class="the-row">
			<div class="col-4 the-mentor">
				<div class="mentorWrapper">
					<span class="head">MENTOR</span>
					<span>KRIYA</span>
					<p class="desc">Molestiae facilisis rem! Parturient. Torquent netus tempora pellentesque tenetur sapiente! Optio beatae est iaculis, veniam leo. Bibendum torquent ducimus, eu.</p>
					<a href="#"><button>VIEW NOW</button></a>
				</div>
			</div>
			<div class="col-4 the-mentor">
				<div class="mentorWrapper">
					<span class="head">MENTOR</span>
					<span>UMKM</span>
					<p class="desc">Molestiae facilisis rem! Parturient. Torquent netus tempora pellentesque tenetur sapiente! Optio beatae est iaculis, veniam leo. Bibendum torquent ducimus, eu.</p>
					<a href="#"><button>VIEW NOW</button></a>
				</div>
			</div>
			<div class="col-4 the-mentor">
				<div class="mentorWrapper">
					<span class="head">MENTOR</span>
					<span>UMKM</span>
					<p class="desc">Molestiae facilisis rem! Parturient. Torquent netus tempora pellentesque tenetur sapiente! Optio beatae est iaculis, veniam leo. Bibendum torquent ducimus, eu.</p>
					<a href="#"><button>VIEW NOW</button></a>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="video" class="blue-bg">
	<div class="container">
		<h3 class="section-title"><span>Kisah Sukses Pelaku</span> <span>UMKM</span></h3>
		<div class="the-row">
			@foreach ($var['videos'] as $video)
			<div class="col-3">
				<div class="video-wraper" style="background-image: url('{{ $video->featured_image }}');">
	        		<a href="{{route('public_galeri').'/'.$video->slug}}"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a>
	        	</div>
				<a href="{{route('public_galeri').'/'.$video->slug}}" class="tilte">{{ $video->title }}</a>
			</div>
			@endforeach
		</div>
	</div>
</section>

<section id="mentor">
	<div class="container">
		<h3 class="section-title gray"><span>Punya Masalah</span> <span>Bisnis?</span></h3>
		<div class="section-desc">
	    	<p>Kami punya lebih dari 100 mentor untuk menjawab semua<br> permasalahan bisnis UMKM anda</p>
		</div>
	</div>
</section>

<section id="questions">
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
<script>
	var mySwiper = new Swiper ('.swiper-container', {
	// Optional parameters
	direction: 'horizontal',
	loop: true,

	// If we need pagination
	pagination: {
	  el: '.swiper-pagination',
	},
	})
</script>
@endsection
