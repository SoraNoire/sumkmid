@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Video</h2>
	</div>
</div>

<div id="video-list">
	<div class="container">
		<div class="the-row">

			@for ($i = 0; $i <=12; $i++)
			<div class="col-4">
				<div class="video-wraper" style="background-image: url('/img/top-video-bg.png');">
	        		<span class="play-button"><i class="fa fa-play fa-lg" aria-hidden="true"></i></span>
	        	</div>
				
			</div>
			@endfor

		</div>
	</div>
</div>
@endsection