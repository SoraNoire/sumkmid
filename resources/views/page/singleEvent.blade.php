@extends('layouts.publicbase')

@section('content')

@php  $meta = PublicHelper::get_event_meta($var['content']->id); @endphp

<div class="breadcrumb">
	<div class="container">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Beranda</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
				<meta itemprop="position" content="1" />
			</li>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="{{ url('/event') }}"><span itemprop="name">Event</span></a>
				<meta itemprop="position" content="2" />
			</li>
		</ol>
	</div>
</div>

<section class="singleVideo" vocab="http://schema.org/" typeof="Event">
	<div class="videoHeading">
		<div class="container">
			<h3 property="name">{{ $var['content']->title }}</h3>
			<div class="postMeta">
				<div class="event-datetime">
						<span property="startDate" content="{{ date('Y-m-d', strtotime($var['meta']->open_at)).'T'.date('G:i', strtotime($var['meta']->open_at)) }}"> {{ date('d M Y', strtotime($var['meta']->open_at)) }}</span>
						<meta property="endDate" content="{{ date('Y-m-d', strtotime($var['meta']->closed_at)).'T'.date('G:i', strtotime($var['meta']->closed_at)) }}">
						<span>{{ date('H:i', strtotime($var['meta']->open_at)) }} WIB - {{ date('d M Y', strtotime($var['meta']->closed_at)) > date('d M Y', strtotime($var['meta']->open_at)) ? date('d M Y H:i', strtotime($var['meta']->closed_at)).' WIB' : date('H:i', strtotime($var['meta']->closed_at)).' WIB' }} </span>
				</div>
			</div>
		</div>
	</div>
	<div class="videoDescription">
		<div class="container">
			<div class="row">
				<div class="col-3 eventPosterColumn">
					<div class="eventPoster">
						<img property="image" src="{{ $var['content']->featured_image }}">
					</div>
				</div>
				<div class="col-9">
					<div class="wrapDescription styledText">
						{!! $var['content']->content !!}
						<div class="eventDetail" property="offers" typeof="Offer">
							<div class="eventMentors">
								@if (count($meta['mentors']) > 0)
								<h4>Mentor : </h4>
								<table>
								<tr class="mentor">
									<td>
										@foreach ($meta['mentors'] as $mentor)
											@if (sizeof($mentor) > 0)
											<div class="mentorWrap">
												@if(isset($mentor->foto_profil))
												<div class="miniPhotoMentor" style="background-image: url('{{ $mentor->foto_profil }}');"></div>
												@else
												<div class="miniPhotoMentor" style="background-image: url('{{ asset('images/admin.png') }}');"></div>
												@endif
												@if (isset($mentor->username))
												<a href="{{ route('public_mentor_single',$mentor->username) }}">{{ $mentor->name ?? 'anonym' }}</a>
												@else
												{{ $mentor->name ?? 'anonym' }}
												@endif
											</div>
											@endif
										@endforeach
									</td>
								</tr>
								</table>
								@endif
							</div>
							<div class="eventDate">
								<h4>Tanggal Acara : </h4>
								<p>{{ date('d M Y', strtotime($var['meta']->open_at)) }},
								{{ date('H:i', strtotime($var['meta']->open_at)) }} WIB - {{ date('d M Y', strtotime($var['meta']->closed_at)) > date('d M Y', strtotime($var['meta']->open_at)) ? date('d M Y H:i', strtotime($var['meta']->closed_at)).' WIB' : date('H:i', strtotime($var['meta']->closed_at)).' WIB' }} </p>
							</div>
							@if ($meta['htm'] != '')
							<div class="eventPrice">
								<h4>Harga Tiket Masuk : </h4>
								<meta property="priceCurrency" content="IDR" />
								<span>
									@if ($meta['htm'] == 'free')
										<p>Gratis</p>
										<meta property="price" content="0" />
									@elseif ( is_array($meta['htm']) && count($meta['htm']) > 0 )
										@foreach ($meta['htm'] as $htm)
	                                    <p> Rp. {{ number_format($htm->nominal) }} - {{ $htm->label }}</p>
	                                    <meta property="price" content="{{$htm->nominal}}" />
	                                    @endforeach
									@endif
								</span>
							</div>
							@endif
							@if ($meta['location'] != '' || $meta['gmaps_url'] != '')
							<div class="eventLocation">
							<h4>Tempat : </h4>
							<p>{{ $meta['location'] }}</p>
								@if ($meta['gmaps_url'] != '')
								<p><i class="fa fa-map-marker" aria-hidden="true">&nbsp;&nbsp;</i><a href="{{ $meta['gmaps_url'] }}">view on google maps</a></p>
								@endif
							</div>
							@endif
							<meta property="url" content="{{ route('public_single_event',$var['content']->slug) }}">
							<meta property="availability" content="{{ ($var['meta']->open_at < date('Y-m-d H:i:s') ? 'no' : 'yes') }}">
							<meta property="validFrom" content="{{ date('Y-m-d', strtotime($var['meta']->open_at)).'T'.date('G:i', strtotime($var['meta']->open_at)) }}">
						</div>
						<div property="location" typeof="Place">
							<meta property="name" content="{{ (isset($meta['location']) && $meta['location'] != '' ? $meta['location'] : 'Indonesia' ) }}">
							<meta property="address" content="{{ (isset($meta['location']) && $meta['location'] != '' ? $meta['location'] : 'Indonesia' ) }}">
						</div>
						<meta property="description" content="{{ app()->Meta->get('meta_desc') }}">

						@if (count($meta['mentors']) > 0)
							@foreach ($meta['mentors'] as $mentor)
								@if (sizeof($mentor) > 0)
									@if (isset($mentor->username))
									<meta property="performer" content="{{ $mentor->name ?? 'anonym' }}">
									@endif
								@endif
							@endforeach
						@else
						<meta property="performer" content="anonim">
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection