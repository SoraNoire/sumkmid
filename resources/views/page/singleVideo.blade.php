@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Video</h2>
	</div>
</div>

<section class="singleVideo">
	<div class="videoHeading">
		<div class="container">
			<h3>{{$var['video']->title}}</h3>
			<div class="postMeta">

				@if(count($var['categories']) > 0)
				<div class="postCategory">
					<span class="icon i-paper"></span>
					@foreach($var['categories'] as $category)
						<span>{{ $category->name }}</span>
					@endforeach
				</div>
				@endif
				@if(count($var['tags']) > 0)
				<div class="postTag">
					<span class="icon i-tag"></span>
					<span>
						@foreach($var['tags'] as $tag)
							<span>{{ $tag->name }}</span>
						@endforeach
					</span>
				</div>
				@endif
			</div>
		</div>
	</div>
	<div class="videoContainer">
		<div class="container">
			<iframe src="{{$var['videoEmbed']}}" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
		</div>
	</div>
	<div class="videoDescription">
		<div class="container">
			<div class="row">
				<div class="col-9">
					<div class="wrapDescription styledText">
					{!! $var['video']->content !!}
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
					<div class="videoNavigation {{ ($var['prevVid'] == '' ? 'singleNavNext' : '') }} {{ ($var['nextVid'] == '' ? 'singleNavPrev' : '') }} ">
						<div class="prevVideo">
							<a href="{{route('public_video').'/'.$var['prevVid']}}">
								<span class="icon i-prev"></span>
								<span>video sebelumnya</span>
							</a>
						</div>
						<div class="nextVideo ">
							<a href="{{route('public_video').'/'.$var['nextVid']}}">
								<span>video selanjutnya</span>
								<span class="icon i-next"></span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="sidebarVideo">
						<div class="sidebar kategoriWidget">
							<h4 class="sidebarDefaultHeading">Kategori</h4>
							<ul>
								<li><a href="#">Branding</a></li>
								<li><a href="#">Marketing</a></li>
								<li><a href="#">Promosi</a></li>
								<li><a href="#">Ide Usaha</a></li>
							</ul>
						</div>
						<div class="sidebar searchVideo">
							<h4 class="sidebarDefaultHeading">Cari Video</h4>
							<div class="searchBox">
								<div class="inputWrap">
									<form action="{{ route('search_video','') }}" method="get">
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

@endsection