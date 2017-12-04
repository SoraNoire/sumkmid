@extends('layouts.publicbase')

@section('content')

<section id="what-we-do">
	<div class="bg-overlay"></div>
	<div class="container">
	    <div class="the-row">
	        <div class="col-5 left-content">
	        	<h3 class="section-title"><span>What</span> <span>We Do</span></h3>
	        	<div class="section-desc">
		        	<p>
		        		Appropriately communicate pandemic initiatives through intuitive testing procedures. Appropriately distinctive paradigms after enabled cloud services. Globally productize sustainable sources with corporate functionalities. Interactively facilitate virtual quality vectors before adaptive e-commerce.
		        	</p>
	        	</div>
	        	<a href="#" class="button">Daftar</a>
	        </div>
	        <div class="col-7 right-content">
	        	<div class="video-wraper" style="background-image: url('/img/top-video-bg.png');">
	        		<span class="play-button"><i class="fa fa-play fa-2x" aria-hidden="true"></i></span>
	        	</div>
	        </div>
	    </div>
    </div>
</section>

<section id="event">
	<div class="container">
	    <div class="the-row">
	        <div class="col-5 left-content">
	        	<h3 class="section-title gray"><span>Event</span> <span>UMKM</span></h3>
	        	<div class="section-desc">
		        	<p>
		        		Completely communicate granular processes whereas ethical ideas. Dynamically streamline high-payoff methodologies and resource-leveling process improvements. Collaboratively create wireless opportunities via high-quality convergence. 
		        	</p>
	        	</div>
	        	<a href="#" class="button blue-shadow">Lihat Event</a>
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

<section id="video" class="blue-bg">
	<div class="container">
		<h3 class="section-title"><span>Kisah Sukses Pelaku</span> <span>UMKM</span></h3>
		<div class="the-row">
			@for ($i = 0; $i <=3; $i++)
			<div class="col-3">
				<div class="video-wraper" style="background-image: url('/img/top-video-bg.png');">
	        		<span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span>
	        	</div>
				<div class="tilte">Sukses Usaha Konveksi Berkat Facebook</div>
			</div>
			@endfor
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
@endsection
