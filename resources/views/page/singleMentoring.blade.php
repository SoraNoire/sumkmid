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
				<span itemprop="name">Materi Mentoring</span> <i class="fa fa-angle-right" aria-hidden="true"></i>
				<meta itemprop="position" content="2" />
			</li>
			<li>
				{{ $mentoring->title }}
			</li>
		</ol>
	</div>
</div>

<section class="singleVideo">
	@if (app()->OAuth->Auth())
	<div class="videoHeading">
		<div class="container">
			<h3>{{$mentoring->title}}</h3>
		</div>
	</div>
	<div class="videoContainer">
		<div class="container">
			@if(isset($postMeta->video_url) && $postMeta->video_url != '')
			<iframe src="{{ $postMeta->video_url }}?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" gesture="media" controls="0" allowfullscreen></iframe>
			@endif
		</div>
	</div>
	<div class="videoDescription">
		<div class="container">
			<div class="row">
				<div class="col-12">
					@if( $mentoring->content != '')
					<div class="wrapDescription">
						{!! $mentoring->content ?? '' !!}
					</div>
					@endif
					@if (count($files) > 0)
					<div class="attachmentVideo" style="display: block;">
						<h4>Unduh Dokumen Pendukung :</h4>
						<ul>
							@foreach($files as $file)
							<li>
								<a download href="{{ PostHelper::getLinkFile($file->file_name, 'files') }}">
									<span class="icon i-download"></span>
									<span>{{ $file->file_label }}</span>
								</a>
							</li>
							@endforeach
						</ul>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	@else
	<div style="text-align: center;padding: 50px;">
		<h2>Access Denied</h2>
		<p>Hanya member Sahabat UMKM yang dapat mengakses halaman ini. Silahkan login atau daftar dahulu.</p>
	</div>
	@endif
</section>

@endsection