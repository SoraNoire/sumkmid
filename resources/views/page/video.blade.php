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
	@for ($i = 0; $i < 12; $i++)
		<div class="cst-3-col">
			<div class="item" style="background-image: url('/img/top-video-bg.png');">
				<a href="#"><span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span></a>
				<div class="item-title">
					<a href="#">Teknik Marketing Usaha Konveksi Konveksi</a>
				</div>		
			</div>
		</div>
	@endfor
	</div>
</div>
@endsection